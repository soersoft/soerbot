<?php

use SoerBot\Commands\Leaderboard\AdvImplementations\LeaderboardRemoveUser;

return function ($client) {
    return new LeaderboardRemoveUser($client);
};
