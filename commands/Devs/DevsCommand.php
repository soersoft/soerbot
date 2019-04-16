<?php

namespace SoerBot\Commands\Devs;

use SoerBot\Commands\Devs\Exceptions\TopicException;
use SoerBot\Commands\Devs\Implementations\TopicModel;
use SoerBot\Commands\Devs\Exceptions\TopicExceptionFileNotFound;

class DevsCommand extends \CharlotteDunois\Livia\Commands\Command
{
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param \CharlotteDunois\Livia\LiviaClient $client
     * @param array $info
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client, $info)
    {
        parent::__construct($client, $info);

        $this->settings = $info;
    }

    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $topic = trim(@$args['topic']);

        if (!empty($topic)) {
            try {
                $path = $this->settings['storePath'];

                $topic = new TopicModel($topic, $path);
                $content = $topic->getContent();
            } catch (TopicExceptionFileNotFound $e) {
                // Exception with low log level: log exception or notify admin with $e->getMessage()
                return $message->say('Команда не найдена.');
            } catch (TopicException $e) {
                // Exception with high log level: log exception or notify admin with $e->getMessage()
                return $message->say('Бот временно не работает. Мы уже занимаемся этой проблемой.');
            } catch (\Throwable $e) {
                // Exception with high log level: log exception or notify admin with $e->getMessage()
                return $message->say('Бот временно не работает. Мы уже занимаемся этой проблемой.');
            }

            return $message->direct($content, ['split' => true])->then(function ($msg) use ($message, $topic) {
                if ($message->message->channel->type !== 'dm') {
                    return $message->reply('Sent you a DM (Direct Message) with ' . $topic . ' information.');
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
