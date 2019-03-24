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
        try {
            $this->topics = new TopicCollection();
        } catch (\Throwable $e) {
            //log error or notify admin
            throw new \Exception('Something wrong with devs command. Got error ' . $e->getMessage() . '.');
        }

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
                    'prompt' => 'Укажите топик: ' . $this->topics->listNames() . '.',
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        if (!empty($args) && $this->topics->has($args['topic'])) {
            if (!($content = $this->topics->getOne($args['topic'])->getContent())) {
                //log error or notify admin
                return $message->say('Команда devs не работает. Мы делаем все возможное, чтобы она снова заработала.');
            }

            return $message->direct($content, ['split' => true])->then(function ($msg) use ($message, $args) {
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

        return $message->say('Укажите топик: ' . $this->topics->listNames() . '.');
    }

    public function serialize()
    {
        return [];
    }
}
