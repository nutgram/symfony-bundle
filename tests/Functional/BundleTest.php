<?php

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\NutgramBundle\NutgramBundle;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

it('uses the token for the bot ID and polling for the console', function () {
    static::bootKernel(['environment' => 'dev']);
    $bot = static::getContainer()->get(Nutgram::class);

    expect($bot->getBotId())->toBe(123456)
        ->and($bot->getRunningMode())->toBe(Polling::class);
});

it('returns an instance', function () {
    /** @var Nutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    expect($instance)->toBeInstanceOf(Nutgram::class);
});

it('returns an instance with the alias', function () {
    /** @var Nutgram $instance */
    $instance = static::getContainer()->get('nutgram');

    expect($instance)->toBeInstanceOf(Nutgram::class);
});

it('rejects the removed botId option when loading bundle configuration', function () {
    $extension = (new NutgramBundle())->getContainerExtension();

    expect(fn () => $extension->load([['config' => ['botId' => 123]]], new ContainerBuilder()))
        ->toThrow(InvalidConfigurationException::class, 'Unrecognized option "botId" under "nutgram.config"');
});
