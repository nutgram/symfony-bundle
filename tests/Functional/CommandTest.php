<?php

use SergiX44\Nutgram\Nutgram;
use SergiX44\NutgramBundle\Console\LogoutCommand;
use SergiX44\NutgramBundle\Console\RegisterCommandsCommand;
use SergiX44\NutgramBundle\Console\RunCommand;
use SergiX44\NutgramBundle\Console\WebhookInfoCommand;
use SergiX44\NutgramBundle\Console\WebhookRemoveCommand;
use SergiX44\NutgramBundle\Console\WebhookSetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

it('call the logout command', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    $commandTester = new CommandTester(new LogoutCommand($instance));
    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('deleteWebhook');
    $instance->assertCalled('close');
    $instance->assertCalled('logOut');
});

it('register the commands', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    $commandTester = new CommandTester(new RegisterCommandsCommand($instance));
    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('setMyCommands');
});

it('calls the run method', function () {
    $mock = $this->getMockBuilder(Nutgram::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['run'])
        ->getMock();

    $mock->expects($this->once())
        ->method('run');

    $commandTester = new CommandTester(new RunCommand($mock));
    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();
});

it('gets webhook infos', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    $commandTester = new CommandTester(new WebhookInfoCommand($instance));
    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('getWebhookInfo');
});

it('call the remove webhook', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    $commandTester = new CommandTester(new WebhookRemoveCommand($instance));
    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('deleteWebhook');
});

it('calls the set webhook', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);
    $parameters = static::getContainer()->get(ParameterBagInterface::class);

    $commandTester = new CommandTester(new WebhookSetCommand($instance, $parameters));
    $commandTester->execute(['url' => 'http://foo.bar']);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('setWebhook');
});