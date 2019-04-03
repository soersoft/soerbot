<?php

use SoerBot\Commands\Vote\VoteCommand;

return function ($client) {
    return new VoteCommand($client);
};
