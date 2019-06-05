<?php

namespace Tests\Commands;

use Tests\TestCase;
use SoerBot\Commands\Karma\Implementations\KarmaListener;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;

class KarmaListenerTest extends TestCase
{
    protected function setUp()
    {
        $this->client = $this->createMock('\CharlotteDunois\Livia\LiviaClient');
        $this->listener = new KarmaListener($this->client);

        parent::setUp();
    }

    public function testSuccessfulIncrementKarma(): void
    {
        $username = 'test';

        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $userModel = $this->createMock('SoerBot\Commands\Karma\Implementations\UserModel');

        $message->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('username')->willReturn($username);
        $userModel->expects($this->once())->method('incrementKarma')->with($username);

        $this->setPrivateVariableValue($this->listener, 'user', $userModel);
        $this->listener->incrementKarma($message);
    }

    public function testFailedIncrementKarma()
    {
        $username = '';

        $message = $this->createMock('CharlotteDunois\Yasmin\Models\Message');
        $user = $this->createMock('CharlotteDunois\Yasmin\Models\User');
        $userModel = $this->createMock('SoerBot\Commands\Karma\Implementations\UserModel');

        $message->expects($this->once())->method('__get')->with('author')->willReturn($user);
        $user->expects($this->once())->method('__get')->with('username')->willReturn($username);
        $userModel->expects($this->once())->method('incrementKarma')->with($username)->willThrowException(new InvalidUserNameException());

        $this->setPrivateVariableValue($this->listener, 'user', $userModel);
        $this->listener->incrementKarma($message);
    }

    public function __sleep()
    {
        $this->command = null;
    }
}
