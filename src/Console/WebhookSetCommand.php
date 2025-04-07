<?php

namespace SergiX44\NutgramBundle\Console;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'nutgram:hook:set',
    description: 'Set the bot webhook',
)]
class WebhookSetCommand extends Command
{
    private Nutgram $bot;
    private ParameterBagInterface $parameters;

    public function __construct(Nutgram $bot, ParameterBagInterface $parameters, ?string $name = null)
    {
        parent::__construct($name);
        $this->bot = $bot;
        $this->parameters = $parameters;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'The webhook full url')
            ->addOption('ip', null, InputOption::VALUE_OPTIONAL, 'Optional destination IP address')
            ->addOption('max-connections', null, InputOption::VALUE_OPTIONAL, 'Optional destination IP address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $url */
        $url = $input->getArgument('url');

        /** @var ?string $ip_address */
        $ip_address = $input->getOption('ip') ?: null;

        /** @var ?string $max_connections */
        $max_connections = $input->getOption('max-connections') ?: 50;

        if (is_numeric($max_connections)) {
            $max_connections = (int)$max_connections;
        }

        $secret = $this->parameters->get('nutgram.config')['webhook_secret'];
        $this->bot->setWebhook($url, ip_address: $ip_address, max_connections: $max_connections, secret_token: $secret);

        $io->info("Bot webhook set with url: $url");

        return Command::SUCCESS;
    }
}
