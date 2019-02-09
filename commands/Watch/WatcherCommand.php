<?php

namespace SoerBot\Commands\Watch;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Watcher\Interfaces\WatcherActorInterface;
use SoerBot\Commands\Watch\WatcherActors\SpideyBotWatcherActor;

class WatcherCommand extends Command
{
    /**
     * Список наблюдателей, кото.
     * @var WatcherActorInterface
     */
    private $watcherActors;

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'watch', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Check every message', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [],
        ]);
        $client->on('message', [$this, 'watch']);

        //TODO: продумать автоматическую загрузку наблюдателей
        $this->watcherActors = [
            new SpideyBotWatcherActor($client),
        ];
    }

    /**
     * Функция отслеживает появление новых сообщений и если они соответствуют условиям, выполняет
     * заданное действие.
     */
    public function watch(Message $message)
    {
        foreach ($this->watcherActors as $actor) {
            if ($actor->isPassRequirements($message)) {
                $actor->run($message);
            }
        }
    }

    public function run(CommandMessage $message, ArrayObject $args, bool $fromPattern)
    {
        return  $message->say('...');
    }
}
