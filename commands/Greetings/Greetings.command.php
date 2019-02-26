<?php

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'greetings', // Give command name
                'aliases' => array(),
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'desc', // Fill the description
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array( // If you need some variables you should either fill this section or remove it
                    array(
                        'key' => 'replaceme',
                        'label' => 'replaceme',
                        'prompt' => 'replaceme',
                        'type' => 'replaceme',
                    )
                )
            ));
        }

        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('Hi there!');
        }
    });
};