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
        $commandCreate = require __DIR__ . '/../../commands/Karma/Karma.command.php';

        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');

        $this->command = $commandCreate($client);

        parent::setUp();
    }

    public function testSimpleResponseToTheDiscord(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $promise = new Promise(function () {
        });

        $commandMessage->expects($this->once())->method('say')->with('Ваша карма: 0')->willReturn($promise);

        $this->command->run($commandMessage, new ArrayObject(), false);
    }
}
