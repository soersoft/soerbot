<?php

namespace SoerBot\Commands\PhpFact\Implementations;

class CommandHelper
{
    public static function getCommandDefaultMessage()
    {
        return 'Input one of the command:' . PHP_EOL . 'fact - get random php fact' . PHP_EOL . 'fact [num] - get php fact by number' . PHP_EOL . 'stat - get php facts statistics' . PHP_EOL . 'list - list all possible command';
    }

    public static function getCommandErrorMessage()
    {
        return 'Something went wrong. Today without interesting PHP facts. Sorry!';
    }

    public static function getCommandNotFoundMessage(string $command)
    {
        return 'The ' . $command . ' is wrong command. Use $phpfact list for right command list.';
    }

    public static function getCommandFactNotFoundMessage(string $position)
    {
        return 'The ' . $position . ' is wrong fact. Use $phpfact stat to find right position number.';
    }
}