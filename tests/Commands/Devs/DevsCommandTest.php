<?php

namespace Tests\Commands;

use ArrayObject;
use SoerBot\Commands\Devs\DevsCommand;
use SoerBot\Commands\Devs\TopicCollection;
use Tests\TestCase;
use React\Promise\Promise;

class DevsCommandTest extends TestCase
{
    /** @var DevsCommand $command */
    private $command;

    private $client;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../../commands/Devs/devs.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testConstructorMakeRightObject()
    {
        $this->assertEquals($this->command->name, 'devs');
        $this->assertEquals($this->command->description, 'Команда $devs выводит важные топики.');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testConstructorMakeObjectWithRightArguments()
    {
        $this->assertEquals(count($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'topic');
        $this->assertEquals($this->command->args[0]['label'], 'topic');
        $this->assertEquals($this->command->args[0]['prompt'], 'Укажите топик: how-to-start.');
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testDevsSayDefaultText()
    {
        $testTopics = (new TopicCollection(__DIR__ . '/testfiles/'));
        $this->setPrivateVariableValue($this->command, 'topics', $testTopics);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('Укажите топик: first, second.')->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => '']), false);
    }

    public function testDevsSayRightTextOnNonExistTopic(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('Укажите топик: how-to-start.')->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'not_exist']), false);
    }

    public function testDevsSayRightTextOnExistedTopic()
    {
        $testTopics = (new TopicCollection(__DIR__ . '/testfiles/'));
        $this->setPrivateVariableValue($this->command, 'topics', $testTopics);

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('say')->with('test file 1' . PHP_EOL)->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'first']), false);
    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
