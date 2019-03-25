<?php

namespace SoerBot\Commands\PhpFact;

use SoerBot\Commands\PhpFact\Implementations\PhpFact;

class PhpFactCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'phpfact', // Give command name
            'aliases' => ['fact'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Show PHP facts from https://github.com/pqr/5minphp-bot.', // Fill the description
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
        try {
            $fact = new PhpFact();
        } catch (\Exception $e) {
            // log exception or notify admin with $e->getMessage()
            return $message->say('Something went wrong. Today without interesting PHP facts. Sorry!');
        }

        return $message->say($fact->get());
    }

    public function serialize()
    {
        return [];
    }
}
