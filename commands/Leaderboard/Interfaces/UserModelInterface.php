<?php

namespace SoerBot\Commands\Leaderboard\Interfaces;

interface UserModelInterface
{
    /**
     * Makes a string from the all user's data.
     * @return string
     */
    public function getLeaderBoardAsString();
}
