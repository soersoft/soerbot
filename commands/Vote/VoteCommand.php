<?php

namespace SoerBot\Commands\Vote;

class VoteCommand extends \CharlotteDunois\Livia\Commands\Command
{

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'vote', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Голосование', // Fill the description
            'guildOnly' => true,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => 
                [        // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'topic',
                    'label' => 'topic',
                    'prompt' => 'Выберите тип голосования custom / default',
                    'type' => 'string',
                ],
                ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {   
        switch ($args['topic'])
            {
            case 'custom':
                return $message->say('Будет доступно в следующей версии');
                break;
            case 'default':           
                return $message->say('**Вопрос**' .\PHP_EOL. 'Голосуем в комментариях' .\PHP_EOL. ':one: **ЗА**' .\PHP_EOL. ':two: **ПРОТИВ**' .\PHP_EOL. ':three: **ВОЗДЕРЖАЛСЯ**');
                break;
            }
    }

}
