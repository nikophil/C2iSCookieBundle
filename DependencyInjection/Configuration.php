<?php

namespace C2is\Bundle\CookieBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('c2is_cookie');

        $rootNode
            ->children()
                ->scalarNode('cookie_name')
                    ->cannotBeEmpty()
                    ->defaultValue('c2is_cookie_accepted')
                ->end()
                ->scalarNode('cookie_expire')
                    ->cannotBeEmpty()
                    ->defaultValue('+3 month')
                ->end()
                ->scalarNode('occurrences')
                    ->validate()
                        ->ifTrue(function($v) {return $v === null || !is_numeric($v) || $v < 0;})
                        ->thenInvalid('occurrences must be an integer equal to or higher than 0.')
                    ->end()
                    ->defaultValue(3)
                ->end()
                ->arrayNode('actions')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('close')
                            ->cannotBeEmpty()
                            ->defaultValue(1)
                        ->end()
                        ->scalarNode('accept')
                            ->cannotBeEmpty()
                            ->defaultValue(3)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
