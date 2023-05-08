<?php

namespace SergiX44\NutgramBundle\DependencyInjection\Factory;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;

class NutgramFactory
{
    public function createNutgram(
        array $config,
        ContainerInterface $container,
        RequestStack $requestStack,
        KernelInterface $kernel,
        ?CacheItemPoolInterface $nutgramCache,
        ?LoggerInterface $nutgramLogger,
        ?LoggerInterface $nutgramConsoleLogger
    ): Nutgram {

        if ($kernel->getEnvironment() === 'test') {
            return Nutgram::fake();
        }

        $isCli = \PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg';

        $configuration = new Configuration(
            clientOptions: $config['client_options'] ?? [],
            container: $container,
            cache: new Psr16Cache($nutgramCache),
            logger: $isCli ? $nutgramConsoleLogger : $nutgramLogger
        );

        $bot = new Nutgram($config['token'], $configuration);

        if ($isCli) {
            $bot->setRunningMode(Polling::class);
        } else {
            $webhook = Webhook::class;

            if ($config['safe_mode'] ?? false) {
                $webhook = new $webhook(fn() => $requestStack->getCurrentRequest()?->getClientIp());
            }

            $bot->setRunningMode($webhook);
        }


        return $bot;
    }
}
