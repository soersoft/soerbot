<?php

namespace SoerBot\Commands\Devs;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{
    /**
     * @var TopicCollection
     */
    protected $topics;

    /**
     * @param \CharlotteDunois\Livia\LiviaClient $client
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
                    'prompt' => 'Укажите топик: ' . $this->topics->getTopicsNames() . '.',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        if (!empty($args) && $this->topics->hasTopic($args['topic'])) {
            return $message->direct($this->topics->getContent($args['topic']), ['split' => true])->then(function ($msg) use ($message, $args) {
                if ($message->message->channel->type !== 'dm') {
                    return $message->reply('Sent you a DM (Direct Message) with ' . $args['topic'] . ' information.');
                }

                return $msg;
            }, function () use ($message) {
                if ($message->message->channel->type !== 'dm') {
                    return $message->reply('Unable to send you the DM (Direct Message). You probably have DMs disabled.');
                }
            });
        }

        return $message->say('Укажите топик: ' . $this->topics->getTopicsNames() . '.');
    }

    public function serialize()
    {
        return [];
    }
}
