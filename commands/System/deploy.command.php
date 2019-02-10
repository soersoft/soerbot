<?php

use SoerBot\Commands\System\DeployCommand;

return function ($client) {
    return new DeployCommand($client);
};
