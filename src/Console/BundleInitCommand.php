<?php

namespace SergiX44\NutgramBundle\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'nutgram:init',
    description: 'Creates the route and the config file',
)]
class BundleInitCommand extends Command
{

    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel, ?string $name = null)
    {
        parent::__construct($name);
        $this->kernel = $kernel;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $configDir = $this->kernel->getProjectDir().'/config/';

        $config = "$configDir/packages/nutgram.yaml";
        if (!file_exists($config)) {
            copy(__DIR__.'/../../config/nutgram.yaml', $config);
        }

        $routes = "$configDir/telegram.php";
        if (!file_exists($routes)) {
            copy(__DIR__.'/../../config/telegram.php', $routes);
        }

        $io->success('Config and routes files created!');

        return Command::SUCCESS;
    }
}
