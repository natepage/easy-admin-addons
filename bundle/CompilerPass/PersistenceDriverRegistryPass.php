<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Bundle\CompilerPass;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use NatePage\EasyAdminAddons\Bundle\Enum\ConfigTag;
use NatePage\EasyAdminAddons\Doctrine\PersistenceDriverManagerRegistry;
use NatePage\EasyAdminAddons\Enum\PersistenceDriver;
use NatePage\EasyAdminAddons\Orm\PersistenceDriverEntityPaginator;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistry;
use NatePage\EasyAdminAddons\Persistence\PersistenceDriverRegistryInterface;
use NatePage\EasyAdminAddons\Provider\AdminAddonsContextProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

final readonly class PersistenceDriverRegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $entityPaginators = [];
        $managerRegistries = [];

        foreach ($container->findTaggedServiceIds(ConfigTag::PersistenceDriverEntityPaginator->value) as $id => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['driver'])) {
                    $entityPaginators[$tag['driver']] = new Reference($id);
                }
            }
        }

        foreach ($container->findTaggedServiceIds(ConfigTag::PersistenceDriverManagerRegistry->value) as $id => $tags) {
            foreach ($tags as $tag) {
                if (isset($tag['driver'])) {
                    $managerRegistries[$tag['driver']] = new Reference($id);
                }
            }
        }

        if (isset($entityPaginators[PersistenceDriver::Default->value]) === false && $container->has(EntityPaginatorInterface::class)) {
            $entityPaginators[PersistenceDriver::Default->value] = new Reference($this->replaceService(
                $container,
                EntityPaginatorInterface::class,
                PersistenceDriverEntityPaginator::class
            ));
        }

        if (isset($managerRegistries[PersistenceDriver::Default->value]) === false && $container->has('doctrine')) {
            $managerRegistries[PersistenceDriver::Default->value] = new Reference($this->replaceService(
                $container,
                'doctrine',
                PersistenceDriverManagerRegistry::class
            ));
        }

        $def = new Definition(PersistenceDriverRegistry::class, [
            '$addonsContextProvider' => new Reference(AdminAddonsContextProviderInterface::class),
            '$entityPaginators' => service_locator($entityPaginators),
            '$managerRegistries' => service_locator($managerRegistries),
        ]);

        $container->setDefinition(PersistenceDriverRegistryInterface::class, $def);
    }

    /**
     * Replaces the given service id with the replacement in the container, and returns the new id.
     */
    private function replaceService(ContainerBuilder $container, string $id, string $replacement): string
    {
        $newId = $id . '_replaced';

        if ($container->hasDefinition($id)) {
            $container->setDefinition($newId, $container->getDefinition($id));
            $container->setDefinition($id, $container->getDefinition($replacement));
        }

        if ($container->hasAlias($id)) {
            $container->setAlias($newId, $container->getAlias($id));
            $container->setAlias($id, $replacement);
        }

        return $newId;
    }
}
