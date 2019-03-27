<?php

use SoerBot\Commands\Leaderboard\Implementations\LeaderboardAddCommand;

return function ($client) {
    return new LeaderboardAddCommand($client);
};
