<?php

namespace SoerBot\Commands\Quiz\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Quiz\Services\QuizStoreJSONFile;

class QuizAddCommand extends Command
{
    const SUCCESS_MESSAGE = 'Вопрос добавлен';

    const FAILURE_MESSAGE = 'Не удалось добавить вопрос';

    /**
     * @var QuizStoreJSONFile
     */
    private $store;

    /**
     * QuizAddCommand constructor.
     *
     * @param \CharlotteDunois\Livia\LiviaClient $client
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'quiz-add', // Give command name
            'aliases' => [],
            'group' => 'games', // Group in ['command', 'util']
            'description' => 'Добавить вопрос в викторину', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'question',
                    'label' => 'question',
                    'prompt' => 'Вопрос:',
                    'type' => 'string',
                ],
                [
                    'key' => 'answer',
                    'label' => 'answer',
                    'prompt' => 'Ответ:',
                    'type' => 'string',
                ],
                [
                    'key' => 'tags',
                    'label' => 'tags',
                    'prompt' => 'Теги:',
                    'type' => 'string',
                ],
            ],
        ]);

        $this->store = new QuizStoreJSONFile();
    }

    /**
     * @param CommandMessage $message
     * @param ArrayObject $args
     * @param bool $fromPattern
     * @return Message|Message[]|ExtendedPromiseInterface|ExtendedPromiseInterface[]|void|null
     */
    public function run(CommandMessage $message, ArrayObject $args, bool $fromPattern)
    {
        return $message->say(
            $this->action($args) ? self::SUCCESS_MESSAGE : self::FAILURE_MESSAGE
        );
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function validateArguments(ArrayObject $args): bool
    {
        return isset($args['question']) && isset($args['answer']) && isset($args['tags']);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function addQuestion(ArrayObject $args): bool
    {
        $this->store->load();

        return $this->store->add([$args['question'], $args['answer'], $args['tags']]);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function action(ArrayObject $args): bool
    {
        return $this->validateArguments($args) && $this->addQuestion($args) && $this->store->save();
    }
}
