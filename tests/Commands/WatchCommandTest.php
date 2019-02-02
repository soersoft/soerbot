<?php
namespace Tests\Commands;

use Tests\TestCase;
use React\Promise\Promise;
use ArrayObject;

class WatchCommandTest extends TestCase
{

    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Watch/watch.command.php';

        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $this->client->expects($this->once())->method('on')->with('message');

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
        $promise = new Promise(function () { });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);
 
        $this->command->run($commandMessage, new ArrayObject(), false);
    }

    public function testWatchMethod(): void
    {
      $this->client->expects($this->once())->method('emit')->with('stop');
      $this->command->watch([]);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}