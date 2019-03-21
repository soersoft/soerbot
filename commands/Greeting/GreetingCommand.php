<?php

namespace SoerBot\Commands\Greeting;

class GreetingCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'hi', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'TestGreetingDenisGeek', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'topic',
                    'label' => 'topic',
                    'prompt' => 'type command: say',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $basePath = dirname(__FILE__);
        switch ($args['topic']) {
            case 'say':
                
                return $message->say($message->author . ', салют!');

                break;
        }

        return  $message->say('Unknown directive, use [say]');
    }

    public function serialize()
    {
        return [];
    }
}
