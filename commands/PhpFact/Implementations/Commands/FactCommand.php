<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommandWithArguments;

class FactCommand extends AbstractCommandWithArguments
{
    /**
     * @var int|null
     */
    private $position;

    /**
     * FactCommand constructor.
     *
     * @param PhpFacts $facts
     * @param array $args
     * @throws CommandWrongUsageException
     */
    public function __construct(PhpFacts $facts, array $args)
    {
        parent::__construct($facts, $args);

        $this->initPosition();
    }

    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string
    {
        if ($this->position === null) {
            return $this->facts->getRandom();
        }

        if ($fact = $this->facts->get($this->position)) {
            return $fact;
        }

        return 'The ' . $this->position . ' is wrong fact. Use $phpfact stat to find right position number.';
    }

    /**
     * Checks if position is valid.
     *
     * @throws CommandWrongUsageException
     * @return void
     */
    protected function initPosition(): void
    {
        if (!isset($this->args['argument'])) {
            $this->position = null;

            return;
        }

        if (!is_numeric($this->args['argument'])) {
            throw new CommandWrongUsageException('Wrong usage of fact command. Check if ' . $this->args['argument'] . ' is correct argument.');
        }

        $this->position = (int)$this->args['argument'];
    }
}
