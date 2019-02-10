<?php

use SoerBot\Commands\Watch\WatcherCommand;

return function ($client) {
    return new WatcherCommand($client);
};
