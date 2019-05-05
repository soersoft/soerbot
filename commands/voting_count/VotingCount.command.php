<?php

use SoerBot\Commands\Voting_count\Implementations\VotingCountCommand;

return function ($client) {
    return new VotingCountCommand($client);
};
