<?php

namespace SergiX44\NutgramBundle\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class NutgramFactory
{
    public function createNutgram(
        array $config,
        ContainerInterface $container,
        RequestStack $requestStack,
        ?TagAwareCacheInterface $nutgramCache,
        ?LoggerInterface $nutgramLogger,
        ?LoggerInterface $nutgramConsoleLogger
    ): Nutgram {
        $isCli = \PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg';

        $bot = new Nutgram($config['token'], array_merge([
            'container' => $container,
            'cache' => $nutgramCache,
            'logger' => $isCli ? $nutgramConsoleLogger : $nutgramLogger,
        ], $config['config'] ?? []));

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
