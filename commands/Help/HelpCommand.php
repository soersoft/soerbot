<?php

namespace SoerBot\Commands\Help;

class HelpCommand extends \CharlotteDunois\Livia\Commands\Command
{
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'help', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Description', // Fill the description
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
                    'prompt' => 'Укажите топик: rules, channels',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $basePath = dirname(__FILE__);
        switch ($args['topic']) {
            case 'rules':
                $helpTopic = \file_get_contents($basePath . '/help.topic/rules.md');

                return $message->say($helpTopic);

                break;
            case 'channels':
                $helpTopic = \file_get_contents($basePath . '/help.topic/channel.md');

                return $message->say($helpTopic);

                break;
        }

        return  $message->say('help [rules|channel]');
    }

    public function serialize()
    {
        return [];
    }
}
