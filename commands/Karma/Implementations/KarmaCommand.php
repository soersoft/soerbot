<?php

namespace SoerBot\Commands\Karma\Implementations;

use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor;

class KarmaCommand extends Command
{
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

        $client->emit('RegisterWatcher', new KarmaWatcherActor($client));
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message->say('Ваша карма: 0');
    }
}
