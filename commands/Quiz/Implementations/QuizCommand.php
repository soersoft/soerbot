<?php

namespace SoerBot\Commands\Quiz\Implementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Quiz\Traits\QuizEndMessages;
use SoerBot\Commands\Quiz\Traits\QuizNextMessages;
use SoerBot\Commands\Quiz\Services\QuizStoreJSONFile;
use SoerBot\Commands\Quiz\Traits\QuizWelcomeMessages;
use SoerBot\Commands\Quiz\Traits\QuizWrongAnswerMessages;
use SoerBot\Commands\Quiz\Traits\QuizCorrectAnswerMessages;

class QuizCommand extends Command
{
    use QuizWelcomeMessages;
    use QuizNextMessages;
    use QuizEndMessages;
    use QuizCorrectAnswerMessages;
    use QuizWrongAnswerMessages;

    private $timeout = 20;
    private $numQuestionsInRound = 5;
    private $store;
    private $questionsLeft;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'quiz', // Give command name
            'aliases' => [''],
            'group' => 'games', // Group in ['command', 'util']
            'description' => 'Тематическая викторина', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [],
        ]);
        $this->questionsLeft = 0;
        $this->store = new QuizStoreJSONFile();
        $this->initActions();
    }

    /**
     * Настраивает обработку событий.
     */
    private function initActions()
    {
        $this->client->on('QuizStart', \Closure::fromCallable([$this, 'quizStartAction']));
        $this->client->on('QuizNext', \Closure::fromCallable([$this, 'quizNextAction']));
        $this->client->on('QuizEnd', \Closure::fromCallable([$this, 'quizEndAction']));
    }

    private function quizStartAction(CommandMessage $message)
    {
        $this->questionsLeft = $this->numQuestionsInRound;
        $this->askQuestionAndCollectAnswers($message, $this->getWelcomeMessage())->then(function () use ($message) {
            if ($this->questionsLeft-- > 0) {
                $this->client->emit('QuizNext', $message);
            }
        });
    }

    private function quizNextAction(CommandMessage $message)
    {
        $this->askQuestionAndCollectAnswers($message, $this->getNextMessage())->then(function () use ($message) {
            if ($this->questionsLeft-- > 0) {
                $this->client->emit('QuizNext', $message);
            } else {
                $this->client->emit('QuizEnd', $message);
            }
        });
    }

    private function quizEndAction(CommandMessage $message)
    {
        $this->questionsLeft = 0;
        $message->say($this->getEndMessage());
    }

    /**
     * Ожидает ответов пользователей.
     */
    private function collectAnswers(CommandMessage $message, $q)
    {
        return new \React\Promise\Promise(function (callable $resolve, callable $reject) use ($message, $q) {
            $message->message->channel->collectMessages(function ($msg) use ($message, $q) {
                return trim(mb_strtolower($msg->content)) === trim(mb_strtolower($q['answer']));
            }, [
                'max' => 1,
                'time' => $this->timeout,
            ])->then(function ($messages) use ($message, $q) {
                if ($messages->count() === 0) {
                    return $message->say($this->getWrongAnswerMessage() . $q['answer']);
                }
                $messages->first()->reply($this->getCorrectAnswerMessage());
            })->done($resolve, $reject);
        });
    }

    /**
     * Задает вопрос и ждет ответа.
     * @param CommandMessage $message - сообщение с командой
     * @return \React\Promise\ExtendedPromiseInterface
     */
    private function askQuestionAndCollectAnswers(CommandMessage $message, string $commentMessage)
    {
        $this->store->load();
        $q = $this->store->get();
        $message->say($commentMessage . \PHP_EOL . \PHP_EOL . '**' . $q['question'] . '**');

        return $this->collectAnswers($message, $q);
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $this->client->emit('QuizStart', $message);
    }
}
