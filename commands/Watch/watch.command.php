<?php

return function ($client) {
    return new class($client) extends \CharlotteDunois\Livia\Commands\Command {
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
        }

        /**
         * Функция отслеживает появление новых сообщений и если они соответствуют условиям, выполняет 
         * заданное действие.
         * Примечание:
         * Это первое приближение будущего решения, в данном классе явно нарушены следующие принципы:
         * - Открытости/закрытости 
         * - SRP
         * - Разделения интерфейсов
         * Следующий шаг - рефакторинг и переосмысливание кода с целью улучшения архитектуры
         */
        public function watch(CharlotteDunois\Yasmin\Models\Message $message)
        {
            if ($message->author->username == 'Spidey Bot' && $message->embeds[0]->color == 3066993) {
                foreach ($message->embeds[0]->fields as $field) {
                    if ($field['name'] == 'Branch' && strpos($field['value'], 'develop') > 0) {
                        $this->client->emit('stop');
                        $this->client->emit('debug', 'it seems test is ok');
                    }
                }
            }
        }

        public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
        {
            return  $message->say('...');
        }
    };
};
