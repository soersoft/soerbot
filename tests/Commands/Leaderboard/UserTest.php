<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Implementations\User;

class UserTest extends TestCase
{
    private $user;

    public function testThatWeCanGetRightString()
    {
        $reward = $this->createMock('SoerBot\Commands\Leaderboard\Implementations\Reward');
        $reward->expects($this->once())->method('__toString')->will($this->returnValue(':smile::smile:'));

        $this->user = new User('Username', []);
        $this->setPrivateVariableValue($this->user, 'rewards', [$reward]);

        $string = '@Username' . PHP_EOL . ':smile::smile:' . PHP_EOL;

        $this->assertEquals($string, $this->user);
    }
}
