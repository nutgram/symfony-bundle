<?php

use SergiX44\Nutgram\Nutgram;
use SergiX44\NutgramBundle\Console\BundleInitCommand;
use SergiX44\NutgramBundle\Console\LogoutCommand;
use SergiX44\NutgramBundle\Console\RegisterCommandsCommand;
use SergiX44\NutgramBundle\Console\RunCommand;
use SergiX44\NutgramBundle\Console\WebhookInfoCommand;
use SergiX44\NutgramBundle\Console\WebhookRemoveCommand;
use SergiX44\NutgramBundle\Console\WebhookSetCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

it('installs the bundled config and routes and preserves edits on a second run', function () {
    $filesystem = new Filesystem();
    $projectDir = sys_get_temp_dir().'/nutgram-init-'.bin2hex(random_bytes(8));
    $configDir = $projectDir.'/config';
    $templates = dirname(__DIR__, 2).'/config';

    try {
        $filesystem->mkdir($configDir.'/packages');
        $kernel = $this->createStub(KernelInterface::class);
        $kernel->method('getProjectDir')->willReturn($projectDir);
        $tester = new CommandTester(new BundleInitCommand($kernel));

        $tester->execute([]);
        $tester->assertCommandIsSuccessful();
        $this->assertFileEquals($templates.'/nutgram.yaml', $configDir.'/packages/nutgram.yaml');
        $this->assertFileEquals($templates.'/telegram.php', $configDir.'/telegram.php');

        $filesystem->dumpFile($configDir.'/packages/nutgram.yaml', 'nutgram: { routes: false }');
        $filesystem->dumpFile($configDir.'/telegram.php', '<?php // Custom bot routes');
        $tester->execute([]);
        $tester->assertCommandIsSuccessful();
        expect(file_get_contents($configDir.'/packages/nutgram.yaml'))->toBe('nutgram: { routes: false }')
            ->and(file_get_contents($configDir.'/telegram.php'))->toBe('<?php // Custom bot routes');
    } finally {
        $filesystem->remove($projectDir);
    }
});

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

it('reports a failure when webhook information is unavailable', function () {
    $bot = $this->createStub(Nutgram::class);
    $bot->method('getWebhookInfo')->willReturn(null);
    $tester = new CommandTester(new WebhookInfoCommand($bot));

    expect($tester->execute([]))->toBe(1)
        ->and($tester->getDisplay())->toContain('Unable to get webhook info');
});

it('removes the webhook with the requested pending update policy', function (array $options, bool $drop) {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);

    $commandTester = new CommandTester(new WebhookRemoveCommand($instance));
    $commandTester->execute($options);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertReply('deleteWebhook', ['drop_pending_updates' => $drop]);
})->with([
    'keep pending updates by default' => [[], false],
    'drop pending updates on request' => [['--drop-pending-updates' => true], true],
]);

it('calls the set webhook', function () {
    /** @var \SergiX44\Nutgram\Testing\FakeNutgram $instance */
    $instance = static::getContainer()->get(Nutgram::class);
    $parameters = static::getContainer()->get(ParameterBagInterface::class);

    $commandTester = new CommandTester(new WebhookSetCommand($instance, $parameters));
    $commandTester->execute(['url' => 'http://foo.bar']);
    $commandTester->assertCommandIsSuccessful();

    $instance->assertCalled('setWebhook');
});
