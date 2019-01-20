<?php

use SoerBot\Commands\Help\HelpCommand;

return function ($client) {
    return new HelpCommand($client);
};
