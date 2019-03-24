<?php

use SoerBot\Commands\Devs\DevsCommand;

return function ($client) {
    return new DevsCommand($client);
};
