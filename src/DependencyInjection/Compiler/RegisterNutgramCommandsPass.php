<?php

namespace SergiX44\NutgramBundle\DependencyInjection\Compiler;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterNutgramCommandsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(Nutgram::class)) {
            return;
        }

        $definition = $container->findDefinition(Nutgram::class);
        $taggedServices = $container->findTaggedServiceIds('nutgram.command');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('registerCommand', [new Reference($id)]);
        }
    }
}
