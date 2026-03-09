<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NatePage\EasyAdminAddons\Listener\CrudAddonsResolverListener;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProvider;
use NatePage\EasyAdminAddons\Provider\CrudAddonsProviderInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    // Crud Addons
    $services->set(CrudAddonsProviderInterface::class, CrudAddonsProvider::class);

    $services
        ->set(CrudAddonsResolverListener::class)
        ->tag('kernel.event_listener', [
            'priority' => 2, // Must be higher than EasyCorp\Bundle\EasyAdminBundle\EventListener\AdminRouterSubscriber
        ]);
};
