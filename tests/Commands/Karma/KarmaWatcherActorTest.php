<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;

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

    public function testFailedIsPassRequirements(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('bot')->willReturn(true);

        $this->assertEquals($this->watcher->isPassRequirements($commandMessage), false);
    }

    public function testSuccessRun(): void
    {
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('username')->willReturn('username');

        $userModel = $this->getMockBuilder('UserModel')->setMethods(['incrementKarma'])->getMock();
        $userModel
            ->expects($this->once())
            ->method('incrementKarma')
            ->with('username')
            ->will($this->returnValue('username'));

        $this->setPrivateVariableValue($this->watcher, 'user', $userModel);

        $this->watcher->run($commandMessage);
    }

    public function testExcpetionRun(): void
    {
        $incorrectUserName = 0;
        $commandMessage = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $commandMessage->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('username')->willReturn($incorrectUserName);

        $userModel = $this->getMockBuilder('UserModel')->setMethods(['incrementUserKarma'])->getMock();
        $userModel
            ->expects($this->once())
            ->method('incrementUserKarma')
            ->with($incorrectUserName)
            ->will($this->throwException(new InvalidUserNameException()));

        $this->expectException(InvalidUserNameException::class);
        $this->setPrivateVariableValue($this->watcher, 'user', $userModel);
        $this->watcher->run($commandMessage);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
