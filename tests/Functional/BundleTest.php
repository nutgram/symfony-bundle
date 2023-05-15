<?php

use SergiX44\Nutgram\Nutgram;

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
