<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NatePage\EasyAdminAddons\Bundle\Enum\ConfigParam;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProvider;
use NatePage\EasyAdminAddons\Context\AdminAddonsContextProviderInterface;
use NatePage\EasyAdminAddons\Doctrine\PersistenceDriverManagerRegistry;
use NatePage\EasyAdminAddons\Field\Configurator\EmbeddedCrudIndexConfigurator;
use NatePage\EasyAdminAddons\Field\Configurator\TextTruncateMiddleConfigurator;
use NatePage\EasyAdminAddons\Field\Configurator\TimezoneConfigurator as FieldTimezoneConfigurator;
use NatePage\EasyAdminAddons\Field\Configurator\TurboFrameConfigurator;
use NatePage\EasyAdminAddons\Filter\Configurator\TimezoneConfigurator as FilterTimezoneConfigurator;
use NatePage\EasyAdminAddons\Helper\EntityDtoHelper;
use NatePage\EasyAdminAddons\Listener\AdminAddonsContextResolverListener;
use NatePage\EasyAdminAddons\Orm\PersistenceDriverEntityPaginator;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistry;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistryInterface;
use NatePage\EasyAdminAddons\Timezone\Resolver\TimezoneResolver;
use NatePage\EasyAdminAddons\Timezone\Resolver\TimezoneResolverInterface;
use NatePage\EasyAdminAddons\Twig\Extension\AdminAddonsContextExtension;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolver;
use NatePage\EasyAdminAddons\Twig\Resolver\TemplateResolverInterface;

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

    // Field Configurator
    $services->set(EmbeddedCrudIndexConfigurator::class);
    $services->set(FieldTimezoneConfigurator::class);
    $services->set(TextTruncateMiddleConfigurator::class);
    $services->set(TurboFrameConfigurator::class);

    // Filter Configurator
    $services->set(FilterTimezoneConfigurator::class);

    // Helper
    $services->set(EntityDtoHelper::class);

    // Persistence
    $services->set(PersistenceDriverRegistryInterface::class, PersistenceDriverRegistry::class);
    $services->set(PersistenceDriverEntityPaginator::class);
    $services->set(PersistenceDriverManagerRegistry::class);

    // Timezone
    $services
        ->set(TimezoneResolverInterface::class, TimezoneResolver::class)
        ->arg('$systemTimezone', param(ConfigParam::TimezoneSystemTimezone->value))
        ->arg('$userTimezone', param(ConfigParam::TimezoneUserTimezone->value));

    // Twig
    $services->set(AdminAddonsContextExtension::class);
    $services->set(TemplateResolverInterface::class, TemplateResolver::class);
};
