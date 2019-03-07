<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Interfaces\UserModelInterface;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

class UserModel implements UserModelInterface
{
    /**
     * @var User[]
     */
    protected $users;

    /**
     * @var string
     */
    protected $linesDelimiter;

    /**
     * UserModel constructor.
     * @param LeaderBoardStoreInterface $store
     * @param string
     */
    public function __construct(LeaderBoardStoreInterface $store, $linesDelimiter = PHP_EOL)
    {
        try {
            $store->load();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        $this->linesDelimiter = $linesDelimiter;

        foreach ($store->toArray() as $user) {
            $this->users[] = new User($user['username'], $user['rewards']);
        }
    }

    /**
     * @return string
     */
    public function getLeaderBoardAsString()
    {
        $str = '';

        foreach ($this->users as $user) {
            $str .= $user . $this->linesDelimiter;
        }

        return $str;
    }
}
