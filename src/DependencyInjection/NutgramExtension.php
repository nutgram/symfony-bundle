<?php

namespace SergiX44\NutgramBundle\DependencyInjection;

use SergiX44\Nutgram\Handlers\Type\Command;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class NutgramExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('nutgram.config', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/'));
        $loader->load('services.yaml');

        $container->registerForAutoconfiguration(Command::class)
            ->addTag('nutgram.command');
    }

    public function getAlias(): string
    {
        return 'nutgram';
    }
}
