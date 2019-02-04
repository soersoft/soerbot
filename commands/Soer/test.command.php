<?php

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
                'name' => 'test',
                'aliases' => [],
                'group' => 'utils',
                'description' => 'Test features command',
                'guildOnly' => false,
                'ownerOnly' => true,
                'throttling' => [
                    'usages' => 5,
                    'duration' => 10,
                ],
                'guarded' => true,
                'args' => [
                    [
                        'key' => 'memberr',
                        'label' => 'memberr',
                        'prompt' => 'What memeber do you mean?',
                        'type' => 'member',
                    ],
                ],
            ]);
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('...');
        }
    };
};
