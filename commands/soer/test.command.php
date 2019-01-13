<?php

return function ($client) {
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command
    {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, array(
                'name' => 'test',
                'aliases' => array(),
                'group' => 'utils',
                'description' => 'Test features command',
                'guildOnly' => false,
                'throttling' => array(
                    'usages' => 5,
                    'duration' => 10
                ),
                'guarded' => true,
                'args' => array(
                    array(
                        'key' => 'memberr',
                        'label' => 'memberr',
                        'prompt' => 'What memeber do you mean?',
                        'type' => 'member'
                    )
                )
            ));
        }

        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('...');
        }
    });
};
