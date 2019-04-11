<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class KarmaCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Karma/Karma.command.php';

        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->command = $commandCreate($client);

        parent::setUp();
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $userName = 'username';

        $commandMessage = $this->createMock('\CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $userModel = $this->createMock('\SoerBot\Commands\Karma\Implementations\UserModel');
        $user = $this->createMock('\CharlotteDunois\Yasmin\Models\User');
        $karmaWatcherActor = $this->createMock('SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor');

        $reflection = new \ReflectionObject($this->command);
        $reflection_property = $reflection->getProperty('karmaWatcherActor');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($this->command, $karmaWatcherActor);

        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('username')->willReturn($userName);
        $karmaWatcherActor->expects($this->once())->method('getUser')->willReturn($userModel);
        $userModel->expects($this->once())->method('getUserKarma')->with($userName)->willReturn(20);

        $commandMessage->expects($this->once())->method('reply')->with('Ваша карма: 20')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }
}
