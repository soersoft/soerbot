<?php

namespace SoerBot\Commands\Devs;

use SoerBot\Commands\Devs\Exceptions\TopicException;
use SoerBot\Commands\Devs\Implementations\TopicModel;
use SoerBot\Commands\Devs\Exceptions\TopicExceptionFileNotFound;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{
    /**
     * @param \CharlotteDunois\Livia\LiviaClient $client
     * @throws \Exception
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
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
                    'prompt' => $this->getDefaultMessage(),
                    'type' => 'string',
                ],
            ],
        ]);
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern, TopicModel $external = null)
    {
        if (!empty($args) && !empty($args['topic'])) {
            try {
                $topic = ($external !== null) ? $external : new TopicModel($args['topic']);
                $content = $topic->getContent();
            } catch (TopicExceptionFileNotFound $e) {
                // Exception with log level: log exception or notify admin with $e->getMessage()
                return $message->say('Команда не найдена.');
            } catch (TopicException $e) {
                // Exception with high log level: log exception or notify admin with $e->getMessage()
                return $message->say('Бот временно не работает. Мы уже занимаемся этой проблемой.');
            } catch (\Throwable $e) {
                // Exception with high log level: log exception or notify admin with $e->getMessage()
                return $message->say('Бот временно не работает. Мы уже занимаемся этой проблемой.');
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

        return $message->say($this->getDefaultMessage());
    }

    public function serialize()
    {
        return [];
    }

    protected function getDefaultMessage(): string
    {
        return 'Укажите топик или list для получения топиков.';
    }
}
