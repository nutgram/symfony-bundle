<?php

namespace SergiX44\Nutgram\Symfony;

use SergiX44\Nutgram\Nutgram;

class NutgramConfigurator
{

    private Nutgram $bot;

    public function __construct(Nutgram $bot)
    {
        $this->bot = $bot;
    }

    public function configure()
    {
        $this->bot->onCommand('start', function (Nutgram $bot) {
            return $bot->sendMessage('Hello, world!');
        })->description('The start command!');
    }

}