<?php

namespace SoerBot\Commands\PhpFact;

class PhpFactCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'phpfact', // Give command name
            'aliases' => ['fact'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Show PHP facts from pqr/5minphp-bot.', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'args' => [],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $factsFile = __DIR__ . '/phpfact.txt';

        if (!file_exists($factsFile)) {
            return $message->say('Something went wrong. Today without interesting PHP facts. Sorry');
        }

        $allFacst = @file($factsFile, FILE_IGNORE_NEW_LINES);
        
        if (empty($allFacst)) {
            return $message->say('Something went wrong. Today without interesting PHP facts. Sorry');
        }

        return  $message->say($allFacst[rand(0, count($allFacst) - 1)]);
    }

    public function serialize()
    {
        return [];
    }
}
