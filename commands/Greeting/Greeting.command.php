<?php

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'greeting', // Give command name
                'aliases' => array(),
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'Приветствие.', // Fill the description
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array()
                 ));
        }

        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('revolmax, салют!');
        }
    });
};