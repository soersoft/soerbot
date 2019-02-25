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
        $commandCreate = require __DIR__ . '/../../commands/Greeting/Greeting.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $this->command = $commandCreate($this->client);

        parent::setUp();
    }

    public function testGreetingBasics()
    {
       $this->assertEquals($this->command->name, 'greeting');
       $this->assertEquals($this->command->description, 'Поприветствовать меня.');
       $this->assertEquals($this->command->groupID, 'utils');
    }

    public function testSimpleResponseToTheDiscord(): void
    {

        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with('@Heisenberg , салют!')->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}