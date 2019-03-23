<?php

namespace SoerBot\Commands\Greeting;

use CharlotteDunois\Livia\Commands\Command;

class GreetingCommand extends Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
                'name' => 'greeting', // Give command name
                'aliases' => [],
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'Выводит сообщение приветствия', // Fill the description
                'guildOnly' => false,
                'throttling' => [
                    'usages' => 5,
                    'duration' => 10,
                ],
                'guarded' => true,
                'args' => [],
            ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message->say($message->author . ', салют!');
    }
}
