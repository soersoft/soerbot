<?php

namespace SoerBot\Commands\PhpFact\Implementations;

class CommandHelper
{
    public static function getCommandDefaultMessage()
    {
        return 'Type fact to get a fact or list to get all possible commands.';
    }

    public static function getCommandErrorMessage()
    {
        return 'Something went wrong. Today without interesting PHP facts. Sorry!';
    }

    public static function getCommandNotFoundMessage(string $command)
    {
        return 'The ' . $command . ' is wrong command. Use $phpfact list for right command list.';
    }
}
