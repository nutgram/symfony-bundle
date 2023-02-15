<?php

namespace SergiX44\NutgramBundle;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\KernelInterface;

class NutgramConfigurator
{

    private Nutgram $bot;
    private KernelInterface $kernel;
    private array $config;

    public function __construct(array $config, Nutgram $bot, KernelInterface $kernel)
    {
        $this->bot = $bot;
        $this->kernel = $kernel;
        $this->config = $config;
    }

    public function configure()
    {
        if (!$this->config['routes']) {
            return;
        }

        $_ = (new FileLocator([
            $this->kernel->getProjectDir() . '/config/',
            __DIR__ . '/../config/'
        ]))->locate('telegram.php');

        if ($_) {
            $bot = $this->bot;
            require $_;
        }
    }

}