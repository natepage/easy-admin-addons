<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use NatePage\DynamoDbRepository\Doctrine\Registry\ManagerRegistry as DynamoDbManagerRegistry;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProviderInterface;

final readonly class DynamoDbManagerRegistryDecorator implements ManagerRegistry
{
    public function __construct(
        private CrudAddonsProviderInterface $crudAddonsProvider,
        private DynamoDbManagerRegistry $dynamoDbManagerRegistry,
        private ManagerRegistry $decorated,
    ) {
    }

    public function getDefaultConnectionName(): string
    {
        return $this->getRegistry()->getDefaultConnectionName();
    }

    public function getConnection(?string $name = null): object
    {
        return $this->getRegistry()->getConnection($name);
    }

    public function getConnections(): array
    {
        return $this->getRegistry()->getConnections();
    }

    public function getConnectionNames(): array
    {
        return $this->getRegistry()->getConnectionNames();
    }

    public function getDefaultManagerName(): string
    {
        return $this->getRegistry()->getDefaultManagerName();
    }

    public function getManager(?string $name = null): ObjectManager
    {
        return $this->getRegistry()->getManager($name);
    }

    public function getManagers(): array
    {
        return $this->getRegistry()->getManagers();
    }

    public function resetManager(?string $name = null): ObjectManager
    {
        return $this->getRegistry()->resetManager($name);
    }

    public function getManagerNames(): array
    {
        return $this->getRegistry()->getManagerNames();
    }

    public function getRepository(string $persistentObject, ?string $persistentManagerName = null): ObjectRepository
    {
        return $this->getRegistry()->getRepository($persistentObject, $persistentManagerName);
    }

    public function getManagerForClass(string $class): ObjectManager|null
    {
        return $this->getRegistry()->getManagerForClass($class);
    }

    private function getRegistry(): ManagerRegistry
    {
        $crudAddons = $this->crudAddonsProvider->getCrudAddons();

        return $crudAddons->useDynamoDb ? $this->dynamoDbManagerRegistry : $this->decorated;
    }
}
