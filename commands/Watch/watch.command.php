<?php

use SoerBot\Commands\Watch\WatcherActors\SpideyBotWatcherActor;

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
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
        public function watch(CharlotteDunois\Yasmin\Models\Message $message)
        {
            foreach ($this->watcherActors as $actor) {
                if ($actor->isPassRequirements($message)) {
                    $actor->run($message);
                }
            }
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('...');
        }
    };
};
