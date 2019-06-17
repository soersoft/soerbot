<?php

namespace SoerBot\Commands\Karma\WatcherActor;

use SoerBot\Watcher\Interfaces\WatcherActorInterface;

class KarmaWatcherActor implements WatcherActorInterface
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;
    }

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     *
     * @return boolean;
     */
    public function isPassRequirements(\CharlotteDunois\Yasmin\Models\Message $message)
    {
        if (!$message->author->bot) {
            return true;
        }

        return false;
    }

    /**
     * Выполняет действие, заложенное в Wathcher.
     *
     * @param $message
     */
    public function run(\CharlotteDunois\Yasmin\Models\Message $message)
    {
//        $this->client->emit('KarmaWatchMessage', $message);
    }
}
