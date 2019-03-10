<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Watch\WatcherActors\Karma\KarmaWatcherActor;

class KarmaWatcherTest extends TestCase
{
    private $command;

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
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('bot')->willReturn(false);

        $this->assertEquals($this->watcher->isPassRequirements($commandMessage), true);
    }

    public function testFaildIsPassRequirements(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('bot')->willReturn(true);

        $this->assertEquals($this->watcher->isPassRequirements($commandMessage), false);
    }

    public function testSuccessRun(): void
    {
        // TODO: реализовать проверку работы метода Run
    }

    public function testExcpetionRun(): void
    {
        // TODO: реализовать проверку выброса исключения
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
