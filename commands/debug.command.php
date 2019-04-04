<?php

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
                'name' => 'debug',
                'aliases' => [],
                'group' => 'utils',
                'description' => 'Test features command',
                'guildOnly' => false,
                'ownerOnly' => false,
                'throttling' => [
                    'usages' => 5,
                    'duration' => 10,
                ],
                'guarded' => true,
                'args' => [
                ],
            ]);
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $roles = '';
            foreach ($message->member->roles as $role) {
                $roles .= $role . ', ';
                $this->client->emit('debug', 'DEBUG: ' . $role);
            }

            return  $message->say(
                $roles
            );
        }
    };
};
