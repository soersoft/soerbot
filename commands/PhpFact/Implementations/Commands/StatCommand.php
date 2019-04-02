<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;

class StatCommand extends AbstractCommand
{
    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string
    {
        return 'We have ' . ($this->facts->count() > 1 ? $this->facts->count() . ' facts' : $this->facts->count() . ' fact') . ' in collection.';
    }
}
