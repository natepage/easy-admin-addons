<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Persistence;

use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProviderInterface;
use Psr\Container\ContainerInterface;

final readonly class PersistenceDriverRegistry implements PersistenceDriverRegistryInterface
{
    public function __construct(
        private AdminAddonsContextProviderInterface $addonsContextProvider,
        private ContainerInterface $entityPaginators,
        private ContainerInterface $managerRegistries,
    ) {
    }

    public function getEntityPaginator(): EntityPaginatorInterface
    {
        return $this->entityPaginators->get($this->resolveDriver());
    }

    public function getManagerRegistry(): ManagerRegistry
    {
        return $this->managerRegistries->get($this->resolveDriver());
    }

    private function resolveDriver(): string
    {
        return $this->addonsContextProvider
            ->getAdminAddonsContext()
            ->getCrudAddons()
            ->persistenceDriver;
    }
}
