<?php

namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class GreetingCommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        // include Discord command which we need to test
        $commandCreate = require __DIR__ . '/../../commands/Greeting/Greeting.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $this->client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testGreetingBasics()
    {
       $this->assertEquals($this->command->name, 'greeting');
       $this->assertEquals($this->command->description, 'Test for Greeting command');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testGreetingArguments()
    {
       $this->assertEquals(sizeof($this->command->args), 1);
       $this->assertArrayHasKey('key', $this->command->args[0]);
       $this->assertArrayHasKey('label', $this->command->args[0]);
       $this->assertArrayHasKey('prompt', $this->command->args[0]);
       $this->assertArrayHasKey('type', $this->command->args[0]);
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}