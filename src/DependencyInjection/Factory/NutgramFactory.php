<?php

namespace SergiX44\Nutgram\Symfony\DependencyInjection\Factory;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use Symfony\Component\HttpFoundation\RequestStack;

class NutgramFactory
{
    public function createNutgram(array $config, RequestStack $requestStack, ?CacheInterface $cache, ?LoggerInterface $logger)
    {
        $request = $requestStack->getCurrentRequest();

        $bot = new Nutgram($config['token'], array_merge([
            'cache' => $cache,
            'logger' => $logger,
        ], $config['config'] ?? []));

        if (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg') {
            $bot->setRunningMode(Polling::class);
        } else {
            $webhook = Webhook::class;
            if ($config['safe_mode'] ?? false) {
                $webhook = new Webhook(fn() => $request->getClientIp());
            }

            $bot->setRunningMode($webhook);
        }


        return $bot;
    }
}
