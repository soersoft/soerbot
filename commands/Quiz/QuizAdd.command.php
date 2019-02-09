<?php

use SoerBot\Commands\Quiz\QuizStoreJSONFile;

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        private $store;

        public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
        {
            parent::__construct($client, [
        'name' => 'quiz-add', // Give command name
        'aliases' => [],
        'group' => 'games', // Group in ['command', 'util']
        'description' => 'Добавить вопрос в викторину', // Fill the description
        'guildOnly' => false,
        'throttling' => [
          'usages' => 5,
          'duration' => 10,
        ],
        'guarded' => true,
        'args' => [ // If you need some variables you should either fill this section or remove it
          [
            'key' => 'question',
            'label' => 'question',
            'prompt' => 'Вопрос:',
            'type' => 'string',
          ],
          [
            'key' => 'answer',
            'label' => 'answer',
            'prompt' => 'Ответ:',
            'type' => 'string',
          ],
          [
            'key' => 'tags',
            'label' => 'tags',
            'prompt' => 'Теги:',
            'type' => 'string',
          ],
        ],
      ]);

            $this->store = new QuizStoreJSONFile();
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            if (isset($args['question']) && isset($args['answer']) && isset($args['tags'])) {
                if ($this->store->add([$args['question'], $args['answer'], $args['tags']])) {
                    $this->store->save();

                    return  $message->say('Вопрос добавлен');
                }
            }

            return  $message->say('Вопрос добавлен');
        }
    };
};
