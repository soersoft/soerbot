<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use React\Promise\Promise;

class TestCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $commandCreate = require __DIR__ . '/../../commands/Soer/test.command.php';

        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $registry = $this->createMock('\CharlotteDunois\Livia\CommandRegistry');
        $types = $this->createMock('\CharlotteDunois\Yasmin\Utils\Collection');

        $types->expects($this->exactly(1))->method('has')->willReturn(true);
        $registry->expects($this->exactly(2))->method('__get')->with('types')->willReturn($types);
        $client->expects($this->exactly(2))->method('__get')->with('registry')->willReturn($registry);

        $this->command = $commandCreate($client);

        parent::setUp();
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('...')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }
}
