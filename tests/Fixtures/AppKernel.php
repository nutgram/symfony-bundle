<?php

namespace SergiX44\NutgramBundle\Tests\Fixtures;

use SergiX44\NutgramBundle\NutgramBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{

    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new NutgramBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/test_config.yaml');
    }
}