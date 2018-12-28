<?php

use SoerBot\Commands\ListCommand;

return function ($client) {
    return new ListCommand($client, []);
};
