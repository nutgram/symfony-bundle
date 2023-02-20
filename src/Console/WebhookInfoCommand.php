<?php

namespace SergiX44\NutgramBundle\Console;

use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'nutgram:hook:info',
    description: 'Get current webhook status',
)]
class WebhookInfoCommand extends Command
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

        $webhookInfo = $this->bot->getWebhookInfo();

        if ($webhookInfo === null) {
            $io->error('Unable to get webhook info');
            return 1;
        }

        $lastErrorDate = null;
        if ($webhookInfo->last_error_date !== null) {
            $lastErrorDate = date('Y-m-d H:i:s', $webhookInfo->last_error_date) . ' UTC';
        }

        $lastSynchronizationErrorDate = null;
        if ($webhookInfo->last_synchronization_error_date !== null) {
            $lastSynchronizationErrorDate = date('Y-m-d H:i:s', $webhookInfo->last_synchronization_error_date) . ' UTC';
        }

        $io->table(['Info', 'Value'], [
            ['url', $webhookInfo->url],
            ['has_custom_certificate', $webhookInfo->has_custom_certificate ? 'true' : 'false'],
            ['pending_update_count', $webhookInfo->pending_update_count],
            ['ip_address', $webhookInfo->ip_address],
            ['last_error_date', $lastErrorDate],
            ['last_error_message', $webhookInfo->last_error_message],
            ['last_synchronization_error_date', $lastSynchronizationErrorDate],
            ['max_connections', $webhookInfo->max_connections],
            ['allowed_updates', implode(', ', $webhookInfo->allowed_updates ?: [])],
        ]);

        return Command::SUCCESS;
    }
}
