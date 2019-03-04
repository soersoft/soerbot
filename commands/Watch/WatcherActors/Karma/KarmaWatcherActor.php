<?php

namespace SoerBot\Commands\Watch\WatcherActors\Karma;

use CharlotteDunois\Yasmin\Models\Message;
use SoerBot\Watcher\Interfaces\WatcherActorInterface;
use SoerBot\Commands\Watch\WatcherActors\Karma\Implementations\UserModel;

class KarmaWatcherActor implements WatcherActorInterface
{
    private $user;

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;
        $this->user = new UserModel();
    }

    /**
     * Проверяет соответствует ли сообщение требованиям Watcher-а.
     *
     * @param $message
     * @return boolean;
     */
    public function isPassRequirements(Message $message)
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
     * @return void
     */
    public function run(Message $message)
    {
        $this->action($message->author->username);
    }

    public function addKarma(string $userName): bool
    {
        return $this->user->setUserKarma($userName);
    }

    public function validateUserName(string $userName)
    {
        return isset($userName) && is_string($userName);
    }

    public function action(string $userName)
    {
        return $this->validateUserName($userName) && $this->addKarma($userName) && $this->user->save();
    }
}
