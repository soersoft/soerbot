<?php

use SoerBot\Commands\Leaderboard\AdvImplementations\LeaderboardAddCommand;

return function ($client) {
    return new LeaderboardAddCommand($client);
};
