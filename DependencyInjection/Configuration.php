<?php

namespace Astina\Bundle\TranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('astina_translation');

        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('domains')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('admin')
                    ->isRequired()
                    ->children()
                        ->scalarNode('layout_template')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
