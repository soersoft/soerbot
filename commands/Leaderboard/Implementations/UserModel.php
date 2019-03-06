<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Interfaces\UserModelInterface;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

class UserModel implements UserModelInterface
{
    public $users;

    public function __construct(LeaderBoardStoreInterface $store)
    {
        $store->load();
    }

    public function getLeaderBoardAsString()
    {
        return true;
    }
}
