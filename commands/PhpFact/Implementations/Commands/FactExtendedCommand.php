<?php

namespace SoerBot\Commands\PhpFact\Implementations\Commands;

use SoerBot\Commands\PhpFact\Implementations\PhpFacts;
use SoerBot\Commands\PhpFact\Abstractions\AbstractCommand;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;

class FactExtendedCommand extends AbstractCommand
{
    private $position;

    public function __construct(PhpFacts $facts, array $args = [])
    {
        if (empty($args['position'])) {
            throw new CommandWrongUsageException('Wrong usage of fact [num] command. Check why num is empty.');
        }

        if (!is_numeric($args['position'])) {
            throw new CommandWrongUsageException('Wrong usage of fact [num] command. Check if ' . $args['position'] . ' is correct argument.');
        }

        $this->position = $args['position'];

        parent::__construct($facts, $args);
    }

    public function response(): string
    {
        if ($fact = $this->facts->get($this->position)) {
            return $fact;
        }

        return 'The ' . $this->position . ' is wrong fact. Use $phpfact stat to find right position number.';
    }
}
