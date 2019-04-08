<?php

namespace SoerBot\Commands\PhpFact\Abstractions;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;

abstract class AbstractCommandWithArguments implements CommandInterface
{
    /**
     * @var PhpFacts
     */
    protected $facts;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * Command constructor.
     *
     * @param PhpFacts $facts
     * @param array $args
     */
    public function __construct(PhpFacts $facts, array $args)
    {
        $this->facts = $facts;
        $this->args = $args;
    }
}
