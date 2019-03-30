<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;

class FactCommand extends AbstractCommand
{
    private $position;

    public function __construct(PhpFacts $facts, array $args = [])
    {
        if (!empty($args['position'])) {
            $this->position = $this->validPosition($args['position']);
        }

        parent::__construct($facts, $args);
    }

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
     * Check if position is valid.
     *
     * @param string $position
     * @throws CommandWrongUsageException
     * @return string
     */
    protected function validPosition(string $position): string
    {
        if (!is_numeric($position)) {
            throw new CommandWrongUsageException('Wrong usage of fact [num] command. Check if ' . $position . ' is correct argument.');
        }

        return $position;
    }
}
