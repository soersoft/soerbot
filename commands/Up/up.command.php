<?php

use SoerBot\Commands\Up\UpCommand;

return function ($client) {
    return new UpCommand($client);
};
