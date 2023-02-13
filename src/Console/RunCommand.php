<?php

namespace SergiX44\Nutgram\Symfony\Console;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'nutgram:run',
    description: 'Start the bot in long polling mode',
)]
class RunCommand extends Command
{

    private Nutgram $bot;

    public function __construct(Nutgram $bot, string $name = null)
    {
        parent::__construct($name);
        $this->bot = $bot;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bot->run();
        return Command::SUCCESS;
    }
}