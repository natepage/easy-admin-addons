<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NatePage\DynamoDbRepository\Doctrine\Registry\ManagerRegistry;
use NatePage\EasyAdminAddons\Doctrine\DynamoDbManagerRegistryDecorator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(ManagerRegistry::class);

    $services
        ->set(DynamoDbManagerRegistryDecorator::class)
        ->decorate('doctrine');
};
