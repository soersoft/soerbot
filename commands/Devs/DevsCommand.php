<?php

namespace SoerBot\Commands\Devs;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'devs', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Описание команды $devs', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'topic',
                    'label' => 'topic',
                    'prompt' => 'Укажите топик: how-to-start, beginner. Чтобы узнать о команде $devs наберите  help',
                     'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $basePath = dirname(__FILE__);
        switch ($args['topic']) {
            case 'how-to-start':
                $helpTopic = \file_get_contents($basePath . '/store/how-to-start.topic.md');

                return $message->direct($helpTopic);

                break;
            case 'beginner':
                $helpTopic = \file_get_contents($basePath . '/store/beginner.topic.md');

                return $message->direct($helpTopic);

                break;

                case 'help':
                $helpTopic = \file_get_contents($basePath . '/readme.devs.md');

                return $message->direct($helpTopic);

                break;

            

               }

        return  $message->reply('Чтобы узнать о команде $devs наберите $devs help');
    }

    public function serialize()
    {
        return [];
    }

    
}
