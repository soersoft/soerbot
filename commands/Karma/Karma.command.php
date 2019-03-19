<?php

use SoerBot\Commands\Karma\Implementations\KarmaCommand;

return function ($client) {
    return new KarmaCommand($client);
};
