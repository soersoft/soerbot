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

        public function watch(CharlotteDunois\Yasmin\Models\Message $arg)
        {
            if ($arg->author->username == 'Spidey Bot' && $arg->embeds[0]->color == 3066993) {
                foreach ($arg->embeds[0]->fields as $field) {
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
