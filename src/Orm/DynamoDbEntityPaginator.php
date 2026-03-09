<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Orm;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\PaginatorDto;

final readonly class DynamoDbEntityPaginator implements EntityPaginatorInterface
{
    public function paginate(PaginatorDto $paginatorDto, QueryBuilder $queryBuilder): EntityPaginatorInterface
    {
        return $this;
    }

    public function generateUrlForPage(int $page): string
    {
        // TODO: Implement generateUrlForPage() method.
    }

    public function getCurrentPage(): int
    {
        // TODO: Implement getCurrentPage() method.
    }

    public function getLastPage(): int
    {
        // TODO: Implement getLastPage() method.
    }

    public function getPageRange(?int $pagesOnEachSide = null, ?int $pagesOnEdges = null): iterable
    {
        // TODO: Implement getPageRange() method.
    }

    public function getPageSize(): int
    {
        // TODO: Implement getPageSize() method.
    }

    public function hasPreviousPage(): bool
    {
        // TODO: Implement hasPreviousPage() method.
    }

    public function getPreviousPage(): int
    {
        // TODO: Implement getPreviousPage() method.
    }

    public function hasNextPage(): bool
    {
        // TODO: Implement hasNextPage() method.
    }

    public function getNextPage(): int
    {
        // TODO: Implement getNextPage() method.
    }

    public function hasToPaginate(): bool
    {
        // TODO: Implement hasToPaginate() method.
    }

    public function isOutOfRange(): bool
    {
        // TODO: Implement isOutOfRange() method.
    }

    public function getNumResults(): int
    {
        // TODO: Implement getNumResults() method.
    }

    public function getResults(): ?iterable
    {
        return [];
    }

    public function getResultsAsJson(): string
    {
        // TODO: Implement getResultsAsJson() method.
    }
}
