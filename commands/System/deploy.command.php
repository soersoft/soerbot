<?php

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
                'name' => 'deploy',
                'aliases' => [],
                'group' => 'utils',
                'description' => 'Deploy command',
                'guildOnly' => false,
                'throttling' => [
                    'usages' => 5,
                    'duration' => 10,
                ],
                'guarded' => true,
            ]);
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $this->client->emit('stop');
        }
    };
};
