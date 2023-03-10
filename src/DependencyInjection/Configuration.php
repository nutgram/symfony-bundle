<?php

namespace SergiX44\NutgramBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nutgram');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('token')->end()
            ->booleanNode('safe_mode')->end()
            ->arrayNode('config')->end()
            ->booleanNode('routes')->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
