<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Orm;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\PaginatorDto;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProviderInterface;

final readonly class DynamoDbEntityPaginatorDecorator implements EntityPaginatorInterface
{
    public function __construct(
        private CrudAddonsProviderInterface $crudAddonsProvider,
        private DynamoDbEntityPaginator $dynamoDbEntityPaginator,
        private EntityPaginatorInterface $decorated,
    ) {
    }

    public function paginate(PaginatorDto $paginatorDto, QueryBuilder $queryBuilder): EntityPaginatorInterface
    {
        $this->getEntityPaginator()->paginate($paginatorDto, $queryBuilder);

        return $this;
    }

    public function generateUrlForPage(int $page): string
    {
        return $this->getEntityPaginator()->generateUrlForPage($page);
    }

    public function getCurrentPage(): int
    {
        return $this->getEntityPaginator()->getCurrentPage();
    }

    public function getLastPage(): int
    {
        return $this->getEntityPaginator()->getLastPage();
    }

    public function getPageRange(?int $pagesOnEachSide = null, ?int $pagesOnEdges = null): iterable
    {
        return $this->getEntityPaginator()->getPageRange($pagesOnEachSide, $pagesOnEdges);
    }

    public function getPageSize(): int
    {
        return $this->getEntityPaginator()->getPageSize();
    }

    public function hasPreviousPage(): bool
    {
        return $this->getEntityPaginator()->hasPreviousPage();
    }

    public function getPreviousPage(): int
    {
        return $this->getEntityPaginator()->getPreviousPage();
    }

    public function hasNextPage(): bool
    {
        return $this->getEntityPaginator()->hasNextPage();
    }

    public function getNextPage(): int
    {
        return $this->getEntityPaginator()->getNextPage();
    }

    public function hasToPaginate(): bool
    {
        return $this->getEntityPaginator()->hasToPaginate();
    }

    public function isOutOfRange(): bool
    {
        return $this->getEntityPaginator()->isOutOfRange();
    }

    public function getNumResults(): int
    {
        return $this->getEntityPaginator()->getNumResults();
    }

    public function getResults(): ?iterable
    {
        return $this->getEntityPaginator()->getResults();
    }

    public function getResultsAsJson(): string
    {
        return $this->getEntityPaginator()->getResultsAsJson();
    }

    private function getEntityPaginator(): EntityPaginatorInterface
    {
        $crudAddons = $this->crudAddonsProvider->getCrudAddons();

        return $crudAddons->useDynamoDb ? $this->dynamoDbEntityPaginator : $this->decorated;
    }
}
