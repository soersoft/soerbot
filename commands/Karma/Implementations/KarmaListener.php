<?php

namespace SoerBot\Commands\Karma\Implementations;

use SoerBot\Commands\Karma\Implementations\UserModel;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;

class KarmaListener
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->client = $client;

        $this->client->on('incrementKarma', function (\CharlotteDunois\Yasmin\Models\Message $message) {
            $this->incrementKarma($message);
        });

        $this->user = new UserModel();
    }

    public function incrementKarma(\CharlotteDunois\Yasmin\Models\Message $message)
    {
        try {
            $this->user->incrementKarma($message->author->username);
        } catch (InvalidUserNameException $error) {
            $this->client->emit('debug', $error->getMessage());
        }
    }
}
