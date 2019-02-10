<?php

namespace SoerBot\Commands\Quiz\Implementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Quiz\Services\QuizStoreJSONFile;

class QuizCommand extends Command
{
    private $store;

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

        $this->store = new QuizStoreJSONFile();
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $this->store->load();
        $q = $this->store->get();

        return $message->say($q['question']);
    }
}
