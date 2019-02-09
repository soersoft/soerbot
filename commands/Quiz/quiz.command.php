<?php

use SoerBot\Commands\Quiz\Implementations\QuizCommand;

return function ($client) {
    return new QuizCommand($client);
};
