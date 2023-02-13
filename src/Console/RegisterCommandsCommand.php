<?php

namespace SergiX44\Nutgram\Symfony\Console;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'nutgram:register-commands',
    description: 'Register the bot commands',
)]
class RegisterCommandsCommand extends Command
{

    private Nutgram $bot;

    public function __construct(Nutgram $bot, string $name = null)
    {
        parent::__construct($name);
        $this->bot = $bot;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->bot->registerMyCommands();

        $io->success('Bot commands set.');

        return Command::SUCCESS;
    }
}
