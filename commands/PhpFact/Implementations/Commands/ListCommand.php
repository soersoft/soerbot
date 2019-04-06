<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Abstractions\AbstractCommandWithoutArguments;

class ListCommand extends AbstractCommandWithoutArguments
{
    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string
    {
        return 'Input one of the command:' . PHP_EOL . 'fact - get random php fact' . PHP_EOL . 'fact [num] - get php fact by number' . PHP_EOL . 'stat - get php facts statistics' . PHP_EOL . 'list - list all possible command';
    }
}
