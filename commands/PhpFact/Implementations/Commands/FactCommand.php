<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;

class FactCommand extends AbstractCommand
{
    private $position;

    /**
     * FactCommand constructor.
     *
     * @param PhpFacts $facts
     * @param array $args
     * @throws CommandWrongUsageException
     */
    public function __construct(PhpFacts $facts, array $args = [])
    {
        $this->position = $this->validPosition($args);

        parent::__construct($facts, $args);
    }

    /**
     * Returns command result.
     *
     * @return string
     */
    public function response(): string
    {
        if (empty($this->position)) {
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
     * @param array $args
     * @throws CommandWrongUsageException
     * @return string
     */
    protected function validPosition(array $args): string
    {
        if (empty($args['position'])) {
            return '';
        }

        if (!is_numeric($args['position'])) {
            throw new CommandWrongUsageException('Wrong usage of fact [num] command. Check if ' . $args['position'] . ' is correct argument.');
        }

        return $args['position'];
    }
}
