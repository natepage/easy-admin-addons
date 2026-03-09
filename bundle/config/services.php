<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NatePage\EasyAdminAddons\Doctrine\PersistenceDriverManagerRegistry;
use NatePage\EasyAdminAddons\Listener\AdminAddonsContextResolverListener;
use NatePage\EasyAdminAddons\Orm\PersistenceDriverEntityPaginator;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistry;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistryInterface;
use NatePage\EasyAdminAddons\Provider\AdminAddonsContextProvider;
use NatePage\EasyAdminAddons\Provider\AdminAddonsContextProviderInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    // Crud Addons
    $services->set(AdminAddonsContextProviderInterface::class, AdminAddonsContextProvider::class);

    $services
        ->set(AdminAddonsContextResolverListener::class)
        ->tag('kernel.event_listener', [
            'priority' => 2, // Must be higher than EasyCorp\Bundle\EasyAdminBundle\EventListener\AdminRouterSubscriber
        ]);

    // Persistence
    $services->set(PersistenceDriverRegistryInterface::class, PersistenceDriverRegistry::class);
    $services->set(PersistenceDriverEntityPaginator::class);
    $services->set(PersistenceDriverManagerRegistry::class);
};
