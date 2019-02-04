<?php

namespace Tests\Commands;

use ArrayObject;
use Tests\TestCase;
use SoerBot\Commands\Up\UpCommand;

class UpCommandTest extends TestCase
{
    public function testEmptyCommandArgs(): void
    {
        $client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $commandMessage = $this->createMock('CharlotteDunois\Livia\CommandMessage');
        $command = new UpCommand($client, []);
        $deferred = new \React\Promise\Deferred();

        $commandMessage->expects($this->once())->method('parseCommandArgs')->willReturn('');
        $commandMessage->expects($this->once())->method('say')->with('Pinging...')->willReturn($deferred->promise());

        $command->run($commandMessage, new ArrayObject(), false);

        $commandMessage->expects($this->once())->method('edit')->with('Параметры отсутствуют')->willReturn('');
        $deferred->resolve($commandMessage);
    }
}
