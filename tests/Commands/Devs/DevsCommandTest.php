<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;
use SoerBot\Commands\Devs\DevsCommand;

class DevsCommandTest extends TestCase
{
    /** @var DevsCommand $command */
    private $command;

    /**
     * Default client prompt and run method message.
     *
     * @var string
     */
    private $message;

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

        $message = $this->getPrivateMethod($this->command, 'getDefaultMessage');
        $this->message = $message->invoke($this->command);

        parent::setUp();
    }

    /**
     * Exceptions.
     */

    /**
     * Corner cases.
     */

    /**
     * Functionality.
     */
    public function testConstructorMakeRightObject()
    {
        $this->assertEquals($this->command->name, 'devs');
        $this->assertEquals($this->command->description, 'Команда $devs выводит важные топики.');
        $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testConstructorMakeRightObjectWithDefaultArguments()
    {
        $this->assertEquals(count($this->command->args), 1);
        $this->assertArrayHasKey('key', $this->command->args[0]);
        $this->assertArrayHasKey('label', $this->command->args[0]);
        $this->assertArrayHasKey('prompt', $this->command->args[0]);
        $this->assertArrayHasKey('type', $this->command->args[0]);

        $this->assertEquals($this->command->args[0]['key'], 'topic');
        $this->assertEquals($this->command->args[0]['label'], 'topic');
        $this->assertEquals($this->command->args[0]['prompt'], $this->message);
        $this->assertEquals($this->command->args[0]['type'], 'string');
    }

    public function testConstructorSetGlobalSettings()
    {
        $settings = $this->getPrivateVariableValue($this->command, 'settings');

        $this->assertNotEmpty($settings);
        $this->assertArrayHasKey('store', $settings);
    }

    public function testRunSayDefaultText()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with($this->message);
        $this->command->run($commandMessage, new ArrayObject(['topic' => '']), false);
    }

    public function testRunSayDefaultTextWhenTopicNotExist(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $commandMessage->expects($this->once())->method('say')->with('Команда не найдена.');
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'not_exist']), false);
    }

    public function testRunSayDefaultTextWhenTopicExist()
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });
        $commandMessage->expects($this->once())->method('direct')->with($this->isType('string'))->willReturn($promise);
        $this->command->run($commandMessage, new ArrayObject(['topic' => 'list']), false);
    }

    // this hack used when test is faild and PHPUnit makes serialization of object properties
    public function __sleep()
    {
        $this->command = null;
    }
}
