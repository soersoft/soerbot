<?php

use SoerBot\Commands\Leaderboard\Implementations\LeaderboardCleanCommand;

return function ($client) {
    return new LeaderboardCleanCommand($client);
};