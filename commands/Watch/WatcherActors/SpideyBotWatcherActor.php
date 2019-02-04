<?php

namespace SoerBot\Commands\Watch\WatcherActors;

use SoerBot\Configurator;
use SoerBot\Watcher\Interfaces\WatcherActorInterface;

class SpideyBotWatcherActor implements WatcherActorInterface
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;
    }

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     * @return boolean;
     */
    public function isPassRequirements(\CharlotteDunois\Yasmin\Models\Message $message)
    {
        $config = Configurator::get('SpideyBot', ['branch' => 'develop', 'color' => 3066993]);
        if ($message->author->username == 'Spidey Bot' && $message->embeds[0]->color == $config['color']) {
            foreach ($message->embeds[0]->fields as $field) {
                if ($field['name'] == 'Branch' && strpos($field['value'], $config['branch']) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Выполняет действие, заложенное в Wathcher.
     *
     * @param $message
     * @return void
     */
    public function run(\CharlotteDunois\Yasmin\Models\Message $message)
    {
        $this->client->emit('stop');
        $this->client->emit('debug', 'it seems test is ok');
    }
}
