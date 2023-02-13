<?php

namespace SergiX44\Nutgram\Symfony\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use Symfony\Component\HttpFoundation\RequestStack;

class NutgramFactory
{
    public function createNutgram(array $config, ContainerInterface $container, RequestStack $requestStack, ?CacheInterface $cache, ?LoggerInterface $logger)
    {
        $bot = new Nutgram($config['token'], array_merge([
            'container' => $container,
            'cache' => $cache,
            'logger' => $logger,
        ], $config['config'] ?? []));

        if (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg') {
            $bot->setRunningMode(Polling::class);
        } else {
            $webhook = Webhook::class;

            if ($config['safe_mode'] ?? false) {
                $request = $requestStack->getCurrentRequest();
                $webhook = new $webhook(fn() => $request->getClientIp());
            }

            $bot->setRunningMode($webhook);
        }


        return $bot;
    }
}
