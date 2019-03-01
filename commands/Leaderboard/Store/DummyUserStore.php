<?php

namespace SoerBot\Commands\Leaderboard\Store;

use SoerBot\Commands\Leaderboard\Interfaces\UserModelInferface;

class DummyUserStore implements UserModelInferface
{
    public function getLeaderBoardAsString()
    {
        return 'list';
    }
}
