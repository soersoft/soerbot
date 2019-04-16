<?php

namespace SoerBot\Commands\PhpFact\Abstractions;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;

abstract class AbstractCommandWithoutArguments implements CommandInterface
{
    /**
     * @var PhpFacts
     */
    protected $facts;

    /**
     * Command constructor.
     *
     * @param PhpFacts $facts
     */
    public function __construct(PhpFacts $facts)
    {
        $this->facts = $facts;
    }
}
