<?php

use SoerBot\Commands\Quiz\QuizStoreJSONFile;

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        private $store;

        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
        'name' => 'quiz', // Give command name
        'aliases' => [''],
        'group' => 'games', // Group in ['command', 'util']
        'description' => 'Тематическая викторина', // Fill the description
        'guildOnly' => false,
        'throttling' => [
          'usages' => 5,
          'duration' => 10,
        ],
        'guarded' => true,
        'args' => [],
      ]);

            $this->store = new QuizStoreJSONFile();
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            $this->store->load();
            $q = $this->store->get();

            return  $message->say($q['question']);
        }
    };
};
