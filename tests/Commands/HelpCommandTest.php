<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class HelpCommandTest extends TestCase
{
    private $command;

    private $client;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Help/help.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testHelpBasics()
    {
        $this->assertEquals($this->command->name, 'help');
        $this->assertEquals($this->command->description, 'Description');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testHelpArguments()
    {
        $this->assertEquals(sizeof($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'topic');
        $this->assertEquals($this->command->args[0]['label'], 'topic');
        $this->assertEquals($this->command->args[0]['prompt'], 'Укажите топик: rules, channels');
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('help [rules|channel]')->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => '']), false);
    }

    public function testHelpRulesArgument(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $rulesContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Help/help.topic/rules.md');
        $commandMessage->expects($this->once())->method('say')->with($rulesContent)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'rules']), false);
    }

    public function testHelpChannelArgument(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $channelContent = \file_get_contents(dirname(__FILE__) . '/../../commands/Help/help.topic/channel.md');
        $commandMessage->expects($this->once())->method('say')->with($channelContent)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'channels']), false);
    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
