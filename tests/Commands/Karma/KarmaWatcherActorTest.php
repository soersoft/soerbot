<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor;

class KarmaWatcherTest extends TestCase
{
    protected function setUp()
    {
        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $this->watcher = new KarmaWatcherActor($this->client);

        parent::setUp();
    }

    public function testSuccessIsPassRequirements(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');

        $commandMessage->expects($this->at(0))->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('bot')->willReturn(false);

        $this->assertEquals($this->watcher->isPassRequirements($commandMessage), true);
    }

    public function testFailedIsPassRequirements(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');

        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('bot')->willReturn(true);

        $this->assertEquals($this->watcher->isPassRequirements($commandMessage), false);
    }

    public function testRun(): void
    {
        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');

        $this->client->expects($this->once())->method('emit')->with('incrementKarma', $message);

        $this->watcher->run($message);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
