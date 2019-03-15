<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class WatchCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Watch/watch.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->client->expects($this->at(0))->method('on')->with('message');
        $this->client->expects($this->at(1))->method('on')->with('RegisterWatcher');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testWatchBasics()
    {
        $this->assertEquals($this->command->name, 'watch');
        $this->assertEquals($this->command->description, 'Check every message');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function testWatchMethod(): void
    {
        $successActor = $this->store = $this->getMockBuilder('WatcherActorInterface')->setMethods(['isPassRequirements', 'run'])->getMock();
        $successActor->expects($this->once())->method('isPassRequirements')->willReturn(true);
        $successActor->expects($this->once())->method('run');

        $faildActor = $this->store = $this->getMockBuilder('WatcherActorInterface')->setMethods(['isPassRequirements', 'run'])->getMock();
        $faildActor->expects($this->once())->method('isPassRequirements')->willReturn(false);
        $faildActor->expects($this->never())->method('run');

        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');

        $this->setPrivateVariableValue($this->command, 'watcherActors', [$successActor, $faildActor]);
        $this->command->watch($message);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
