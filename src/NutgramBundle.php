<?php

namespace SergiX44\NutgramBundle;

use SergiX44\NutgramBundle\DependencyInjection\NutgramExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NutgramBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new NutgramExtension();
    }
}
