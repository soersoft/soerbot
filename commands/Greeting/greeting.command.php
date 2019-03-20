<?php

use SoerBot\Commands\Greeting\GreetingCommand;

return function ($client) {
    return new GreetingCommand($client);
};
