<?php

use SoerBot\Commands\Leaderboard\AdvImplementations\LeaderboardCommand;

return function ($client) {
    return new LeaderboardCommand($client);
};
