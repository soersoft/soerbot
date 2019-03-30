<?php

namespace SoerBot\Commands\PhpFact\Abstractions;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;

abstract class AbstractCommand
{
    protected $facts;

    /**
     * Command constructor.
     *
     * @param PhpFacts $facts
     * @param array $args
     */
    public function __construct(PhpFacts $facts, array $args = [])
    {
        $this->facts = $facts;
    }

    /**
     * Returns command result.
     *
     * @return string
     */
    abstract public function response(): string;
}
