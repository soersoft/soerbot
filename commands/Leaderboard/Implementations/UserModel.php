<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Interfaces\UserModelInterface;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

class UserModel implements UserModelInterface
{
    /**
     * @var SoerBot\Commands\Leaderboard\Implementations\User[]
     */
    public $users;

    /**
     * UserModel constructor.
     * @param LeaderBoardStoreInterface $store
     */
    public function __construct(LeaderBoardStoreInterface $store)
    {
        try {
            $store->load();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     * @return string
     */
    public function getLeaderBoardAsString()
    {
        return '';
    }
}
