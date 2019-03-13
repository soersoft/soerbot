<?php

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
                'name' => 'karma', // Give command name
                'aliases' => [],
                'group' => 'utils', // Group in ['command', 'util']
                'description' => 'Выводит состояние кармы пользователя', // Fill the description
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
            return $message->say('Ваша карма: 0');
        }
    };
};
