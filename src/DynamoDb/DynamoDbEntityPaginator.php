<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\DynamoDb;

use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\PaginatorDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGeneratorInterface;
use NatePage\DynamoDbRepository\Common\Registry\ObjectRepositoryRegistryInterface;
use NatePage\DynamoDbRepository\Common\Repository\ObjectRepositoryInterface;
use NatePage\EasyAdminAddons\Provider\AdminAddonsContextProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ResetInterface;

final class DynamoDbEntityPaginator implements EntityPaginatorInterface, ResetInterface
{
    private const string QUERY_LAST_EVALUATED_KEY = 'lastEvaluatedKey';

    private ?QueryBuilder $queryBuilder = null;

    private ?PaginatorDto $paginatorDto = null;

    private ?ObjectRepositoryInterface $objectRepository = null;

    private ?array $results = null;

    public function __construct(
        private readonly AdminAddonsContextProviderInterface $addonsContextProvider,
        private readonly AdminUrlGeneratorInterface $adminUrlGenerator,
        private readonly ObjectRepositoryRegistryInterface $objectRepositoryRegistry,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function paginate(PaginatorDto $paginatorDto, QueryBuilder $queryBuilder): EntityPaginatorInterface
    {
        $this->paginatorDto = $paginatorDto;
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    public function generateUrlForPage(int $page): string
    {
        $currentRequest = $this->getCurrentRequest();

        return $this->adminUrlGenerator
            ->set(EA::PAGE, $page)
            ->setController($currentRequest->attributes->get(EA::CRUD_CONTROLLER_FQCN))
            ->setAction($currentRequest->attributes->get(EA::CRUD_ACTION))
            ->set(self::QUERY_LAST_EVALUATED_KEY, $this->objectRepository?->lastEvaluatedKey)
            ->generateUrl();
    }

    public function getCurrentPage(): int
    {
        return \max(1, $this->paginatorDto?->getPageNumber() ?? 1);
    }

    public function getLastPage(): int
    {
        return $this->getCurrentPage();
    }

    public function getPageRange(?int $pagesOnEachSide = null, ?int $pagesOnEdges = null): iterable
    {
        return [];
    }

    public function getPageSize(): int
    {
        return $this->paginatorDto?->getPageSize() ?? 50;
    }

    public function hasPreviousPage(): bool
    {
        // We allow only forward pagination
        return false;
    }

    public function getPreviousPage(): int
    {
        return \max(1, $this->getCurrentPage() - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->objectRepository?->lastEvaluatedKey !== null;
    }

    public function getNextPage(): int
    {
        return $this->getCurrentPage() + 1;
    }

    public function hasToPaginate(): bool
    {
        return $this->getNumResults() > $this->getPageSize();
    }

    public function isOutOfRange(): bool
    {
        return false;
    }

    public function getNumResults(): int
    {
        $currentPageCount = \count($this->getResults() ?? []);

        if ($this->getCurrentPage() < 2) {
            return $currentPageCount;
        }

        // Current page items + all previous pages items (assuming full pages)
        return (int)\round($currentPageCount + ($this->getCurrentPage() - 1) * $this->getPageSize());
    }

    public function getResults(): ?iterable
    {
        if ($this->results !== null) {
            return $this->results;
        }

        $crudAddons = $this->addonsContextProvider->getAdminAddonsContext()->getCrudAddons();

        $getResultsCallback = $crudAddons->entityPaginatorGetResultsCallback;
        if (\is_callable($getResultsCallback) === false) {
            throw new \RuntimeException('The paginator cannot get the results because the "entityPaginatorGetResultsCallback" callback is not configured in the CrudAddons.');
        }

        $from = $this->queryBuilder?->getDQLPart('from')[0] ?? null;
        $objectClass = $from instanceof From ? $from->getFrom() : null;

        $this->objectRepository = $this->objectRepositoryRegistry->get($objectClass);

        $currentRequest = $this->getCurrentRequest();
        $callbackParams = [
            'objectRepository' => $this->objectRepository,
            'queryBuilder' => $this->queryBuilder,
            'paginatorDto' => $this->paginatorDto,
            'lastEvaluatedKey' => $currentRequest->query->get(self::QUERY_LAST_EVALUATED_KEY),
        ];

        $results = $getResultsCallback(...$callbackParams);
        if (\is_array($results) === false && $results instanceof \Traversable === false) {
            throw new \RuntimeException(sprintf('The paginator "entityPaginatorGetResultsCallback" callback must return an array or an instance of Traversable, "%s" returned.', get_debug_type($results)));
        }

        return $this->results = \is_array($results) ? $results : \iterator_to_array($results);
    }

    public function getResultsAsJson(): string
    {
        // TODO: Implement getResultsAsJson() method.
    }

    public function reset(): void
    {
        $this->queryBuilder = null;
        $this->paginatorDto = null;
        $this->objectRepository = null;
        $this->results = null;
    }

    private function getCurrentRequest(): Request
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (null === $currentRequest) {
            throw new \RuntimeException('There is no current HTTP request.');
        }

        return $currentRequest;
    }
}
