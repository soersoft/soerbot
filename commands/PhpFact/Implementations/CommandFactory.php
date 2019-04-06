<?php

namespace SoerBot\Commands\PhpFact\Implementations;

use SoerBot\Commands\PhpFact\Abstractions\CommandInterface;
use SoerBot\Commands\PhpFact\Exceptions\CommandNotFoundException;
use SoerBot\Commands\PhpFact\Implementations\Commands\FactCommand;
use SoerBot\Commands\PhpFact\Implementations\Commands\ListCommand;
use SoerBot\Commands\PhpFact\Implementations\Commands\StatCommand;
use SoerBot\Commands\PhpFact\Exceptions\CommandWrongUsageException;

class CommandFactory
{
    /**
     * Build command objects.
     *
     * @param PhpFacts $facts
     * @param string $input
     * @throws CommandNotFoundException|CommandWrongUsageException
     * @return CommandInterface
     */
    public static function build(PhpFacts $facts, string $input): CommandInterface
    {
        if (preg_match('/^(?<command>[a-z]+)(?:\s+(?<argument>.*))?$/iSu', $input, $match)) {
            array_shift($match);

            if ('fact' === $match['command']) {
                return new FactCommand($facts, $match);
            }

            if ('stat' === $match['command']) {
                return new StatCommand($facts);
            }

            if ('list' === $match['command']) {
                return new ListCommand($facts);
            }

            throw new CommandNotFoundException(CommandHelper::getCommandNotFoundMessage($input));
        }

        throw new CommandNotFoundException(CommandHelper::getCommandNotFoundMessage($input));
    }
}
