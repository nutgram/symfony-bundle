<?php

namespace SergiX44\Nutgram\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
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
