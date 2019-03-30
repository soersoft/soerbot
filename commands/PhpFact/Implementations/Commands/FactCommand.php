<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;

class FactCommand extends AbstractCommand
{
    public function response(): string
    {
        return $this->facts->getRandom();
    }
}
