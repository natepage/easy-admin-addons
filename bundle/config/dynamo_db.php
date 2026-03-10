<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NatePage\DynamoDbRepository\Doctrine\Registry\ManagerRegistry as DynamoDbManagerRegistry;
use NatePage\EasyAdminAddons\Bundle\Enum\ConfigTag;
use NatePage\EasyAdminAddons\DynamoDb\DynamoDbEntityPaginator;
use NatePage\EasyAdminAddons\Enum\PersistenceDriver;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(DynamoDbEntityPaginator::class)
        ->arg('$argumentResolver', service('argument_resolver'))
        ->tag(ConfigTag::PersistenceDriverEntityPaginator->value, [
            'driver' => PersistenceDriver::DynamoDb->value,
        ]);

    $services
        ->set(DynamoDbManagerRegistry::class)
        ->tag(ConfigTag::PersistenceDriverManagerRegistry->value, [
            'driver' => PersistenceDriver::DynamoDb->value,
        ]);
};
