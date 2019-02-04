<?php

namespace SoerBot\Watcher\Interfaces;

interface WatcherActorInterface
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client);

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     * @return boolean;
     */
    public function isPassRequirements(\CharlotteDunois\Yasmin\Models\Message $message);

    /**
     * Выполняет действие, заложенное в Wathcher.
     *
     * @param $message
     * @return void
     */
    public function run(\CharlotteDunois\Yasmin\Models\Message $message);
}
