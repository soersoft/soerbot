<?php

namespace Tests\Commands\Leaderboard;

use Tests\TestCase;
use SoerBot\Commands\Leaderboard\Implementations\Reward;

class RewardTest extends TestCase
{
    public function testThatWeCanGetRightString()
    {
        $reward = new Reward(':smile:', 3);
        $this->assertEquals(':smile::smile::smile:', $reward);
    }
}
