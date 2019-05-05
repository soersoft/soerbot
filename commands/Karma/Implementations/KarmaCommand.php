<?php

namespace SoerBot\Commands\Karma\Implementations;

use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Karma\WatcherActor\KarmaWatcherActor;
use SoerBot\Commands\Karma\Implementations\UserModel;

class KarmaCommand extends Command
{
    private $karmaWatcherActor;

    private $user;

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
            'args' => []
        ]);

        $this->karmaWatcherActor = $this->createKarmaWatcherActor($client);

        $this->user = new UserModel();

        $client->emit('RegisterWatcher', $this->karmaWatcherActor);

        $client->on('KarmaWatchMessage', function ($message) {
            $this->incrementKarma($message);
        });
    }

    private function incrementKarma(\CharlotteDunois\Yasmin\Models\Message $message)
    {
        $this->user->incrementKarma($message->author->username);
    }

    private function createKarmaWatcherActor($client)
    {
        return new KarmaWatcherActor($client);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $karma = $this->user->getKarma($message->author->username);

        return $message->reply("Ваша карма: $karma");
    }
}
