<?php

namespace SergiX44\NutgramBundle;

use SergiX44\NutgramBundle\DependencyInjection\Compiler\RegisterNutgramCommandsPass;
use SergiX44\NutgramBundle\DependencyInjection\NutgramExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NutgramBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new NutgramExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterNutgramCommandsPass());
    }
}
