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
        $isCli = \PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg';

        $configuration = new Configuration(
            apiUrl: $config['config']['apiUrl'],
            botId: $config['config']['botId'],
            botName: $config['config']['botName'],
            testEnv: $config['config']['testEnv'],
            clientTimeout: $config['config']['clientTimeout'],
            clientOptions: $config['config']['clientOptions'] ?? [],
            container: $container,
            cache: new Psr16Cache($nutgramCache),
            logger: $isCli ? $nutgramConsoleLogger : $nutgramLogger,
            localPathTransformer: $config['config']['localPathTransformer'],
            pollingTimeout: $config['config']['pollingTimeout'],
            pollingLimit: $config['config']['pollingLimit'],
        );

        if ($kernel->getEnvironment() === 'test') {
            return Nutgram::fake(config: $configuration);
        }

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
