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
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'topic',
                    'label' => 'topic',
                    'prompt' => 'Выберите тип голосования custom / default',
                    'type' => 'string',
                ],
                [
                    'key' => 'answer',
                    'label' => 'answer',
                    'prompt' => 'Выберите вариант ответа:',
                    'type' => 'string',
                ],
                
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $basePath = dirname(__FILE__);
        switch ($args['topic']) {
            case 'custom':
                $voteTopic = \file_get_contents($basePath . '/vote.topic/custom.md');

                return $message->say($voteTopic);

                break;
            case 'default':
                $message->say('введите название голосования');
                $voteTopic = \file_get_contents($basePath . '/vote.topic/default.md');
                $voteVar = 2;
                $voteTime = 5;
                $voteAnonymous = TRUE;          
                $message->say($voteTopic);
                switch ($args['answer']) {
                    case 'vote1':
                        return $message->say('вы проголосовали за 1 вариант ответа');
        
                        break;
                        case 'vote2':
                        return $message->say('вы проголосовали за 2 вариант ответа');
        
                        break;

                }
                break;
        }

 #       return  $message->say('vote [custom|default]');
    }

    public function serialize()
    {
        return [];
    }
}
