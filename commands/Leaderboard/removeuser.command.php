<?php

use SoerBot\Commands\Leaderboard\Implementations\LeaderboardRemoveUser;

return function ($client) {
    return new LeaderboardRemoveUser($client);
};
