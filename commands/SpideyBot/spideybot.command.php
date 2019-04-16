<?php

use SoerBot\Commands\SpideyBot\Implementations\SpideyBotCommand;

return function ($client) {
    return new SpideyBotCommand($client);
};
