<?php

use SoerBot\Commands\Leaderboard\Implementations\LeaderboardCommand;

return function ($client) {
    return new LeaderboardCommand($client);
};
