<?php

namespace SoerBot\Commands\Devs;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{

    /**
     * @var TopicModel
     */
    protected $topics;

    /**
     * @param \CharlotteDunois\Livia\LiviaClient $client
     *
     * @throws \Exception
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        $this->topics = new TopicCollection();

        parent::__construct($client, [
            'name' => 'devs', // Give command name
            'aliases' => ['dev'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Команда $devs выводит важные топики.', // Fill the description
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
                    'prompt' => 'Укажите топик: ' . $this->topics->getNames() . '.',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        if (!empty($args) && array_key_exists($args['topic'], $this->topics->getTopics())) {
            return $message->say($this->topics->getContent($args['topic']));
        }

        return $message->say('Укажите топик: ' . $this->topics->getNames() . '.');
    }

    public function serialize()
    {
        return [];
    }
}
