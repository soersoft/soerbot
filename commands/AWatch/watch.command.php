<?php

use SoerBot\Commands\AWatch\WatcherCommand;

return function ($client) {
    return new WatcherCommand($client);
};
