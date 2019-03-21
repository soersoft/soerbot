<?php

use SoerBot\Commands\PhpFact\PhpFactCommand;

return function ($client) {
    return new PhpFactCommand($client);
};
