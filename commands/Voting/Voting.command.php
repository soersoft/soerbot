<?php

use SoerBot\Commands\Voting\Implementations\VotingCommand;

return function ($client) {
    return new VotingCommand($client);
};
