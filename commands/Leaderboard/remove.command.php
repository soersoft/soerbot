<?php

use SoerBot\Commands\Leaderboard\Implementations\LeaderboardRemoveRewardsByTypeCommand;

return function ($client) {
    return new LeaderboardRemoveRewardsByTypeCommand($client);
};
