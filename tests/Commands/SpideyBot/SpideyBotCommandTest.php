<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class SpideyBotCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/SpideyBot/spideybot.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->client->expects($this->once())->method('emit')->with('RegisterWatcher');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testSpideyBotBasics()
    {
        $this->assertEquals($this->command->name, 'spidey-bot');
        $this->assertEquals($this->command->description, 'Github CI bot');
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

    public function __sleep()
    {
        $this->command = null;
    }
}
