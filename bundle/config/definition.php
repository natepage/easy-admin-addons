<?php
declare(strict_types=1);

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition) {
    $definition->rootNode()
        ->children()
            ->arrayNode('dynamo_db')
                ->canBeEnabled()
            ->end()
            ->arrayNode('timezone')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('system_timezone')->defaultNull()->end()
                    ->scalarNode('user_timezone')->defaultNull()->end()
                ->end()
            ->end()
        ->end();
};
