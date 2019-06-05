<?php

namespace SoerBot\Commands\Karma\Implementations;

use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;

class KarmaCommand extends Command
{
    /**
     * @var \SoerBot\Commands\Karma\Implementations\UserModel
     */
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
            'args' => [],
        ]);

        $this->user = new UserModel();
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern): \React\Promise\ExtendedPromiseInterface
    {
        try {
            $karma = $this->user->getKarma($message->author->username);

            return $message->reply("Ваша карма: $karma");
        } catch (InvalidUserNameException $error) {
            $this->client->emit('debug', $error->getMessage());
        }
    }
}
