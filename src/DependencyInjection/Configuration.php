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
				->scalarNode('webhook_secret')->defaultNull()->end()
                ->booleanNode('routes')->end()
                ->arrayNode('config')
                ->children()
                    ->scalarNode('apiUrl')->defaultValue(\SergiX44\Nutgram\Configuration::DEFAULT_API_URL)->end()
                    ->scalarNode('botId')->defaultNull()->end()
                    ->scalarNode('botName')->defaultNull()->end()
                    ->booleanNode('testEnv')->defaultFalse()->end()
                    ->scalarNode('clientTimeout')->defaultValue(\SergiX44\Nutgram\Configuration::DEFAULT_CLIENT_TIMEOUT)->end()
                    ->arrayNode('clientOptions')->end()
                    ->scalarNode('localPathTransformer')->defaultNull()->end()
                    ->scalarNode('pollingTimeout')->defaultValue(\SergiX44\Nutgram\Configuration::DEFAULT_POLLING_TIMEOUT)->end()
                    ->scalarNode('pollingLimit')->defaultValue(\SergiX44\Nutgram\Configuration::DEFAULT_POLLING_LIMIT)->end()
                    ->arrayNode('pollingAllowedUpdates')->end()
                    ->booleanNode('enableHttp2')->defaultValue(\SergiX44\Nutgram\Configuration::DEFAULT_ENABLE_HTTP2)->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
