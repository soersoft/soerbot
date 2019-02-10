<?php

use SoerBot\Commands\Quiz\Implementations\QuizAddCommand;

return function ($client) {
    return new QuizAddCommand($client);
};
