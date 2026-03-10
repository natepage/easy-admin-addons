<?php
declare(strict_types=1);

namespace NatePage\EasyAdminAddons\Bundle;

use NatePage\DynamoDbRepository\Doctrine\Registry\ManagerRegistry as DynamoDbManagerRegistry;
use NatePage\EasyAdminAddons\Bundle\CompilerPass\PersistenceDriverRegistryPass;
use NatePage\EasyAdminAddons\Bundle\Enum\ConfigParam;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class EasyAdminAddonsBundle extends AbstractBundle
{
    public function __construct()
    {
        $this->path = \realpath(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new PersistenceDriverRegistryPass());
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('config/definition.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container
            ->parameters()
            ->set(ConfigParam::TimezoneSystemTimezone->value, $config['timezone']['system_timezone'])
            ->set(ConfigParam::TimezoneUserTimezone->value, $config['timezone']['user_timezone']);

        $container->import('config/services.php');

        if ($config['dynamo_db']['enabled']) {
            if (\class_exists(DynamoDbManagerRegistry::class) === false) {
                throw new \LogicException(
                    'The "dynamo_db" configuration is enabled, but the "natepage/dynamo-db-repository" '
                    . 'package is not installed. Please install it to use this feature.'
                );
            }

            $container->import('config/dynamo_db.php');
        }
    }
}
