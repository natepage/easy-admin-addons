<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use NatePage\DynamoDbRepository\Doctrine\Registry\ManagerRegistry;
use NatePage\EasyAdminAddons\Doctrine\DynamoDbManagerRegistryDecorator;
use NatePage\EasyAdminAddons\Orm\DynamoDbEntityPaginator;
use NatePage\EasyAdminAddons\Orm\DynamoDbEntityPaginatorDecorator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    // Doctrine itself
    $services->set(ManagerRegistry::class);

    $services
        ->set(DynamoDbManagerRegistryDecorator::class)
        ->decorate('doctrine');

    // EntityPaginator
    $services->set(DynamoDbEntityPaginator::class);

    $services
        ->set(DynamoDbEntityPaginatorDecorator::class)
        ->decorate(EntityPaginatorInterface::class);
};
