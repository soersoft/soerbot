<?php

namespace SoerBot\Commands\Voting\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Voting\Services\VotingStoreJSONFile;

class VotingCommand extends Command
{
    const SUCCESS_MESSAGE = 'Голосование добавлено';

    const FAILURE_MESSAGE = 'Не удалось добавить голосование';

    /**
     * @var VotingStoreJSONFile
     */
    private $store;

    /**
     * VotingCommand constructor.
     *
     * @param \CharlotteDunois\Livia\LiviaClient $client
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'voting', // Give command name
            'aliases' => [],
            'group' => 'games', // Group in ['command', 'util']
            'description' => 'Голосование:', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ // If you need some variables you should either fill this section or remove it
                [
                    'key' => 'voting',
                    'label' => 'voting',
                    'prompt' => 'Голосование',
                    'type' => 'string',
                ],
                [
                    'key' => 'answer',
                    'label' => 'answer',
                    'prompt' => 'вариант:',
                    'type' => 'string',
                ],
                
            ],
        ]);

        $this->store = new VotingStoreJSONFile();
    }

    /**
     * @param CommandMessage $message
     * @param ArrayObject $args
     * @param bool $fromPattern
     * @return Message|Message[]|ExtendedPromiseInterface|ExtendedPromiseInterface[]|void|null
     */
    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern)
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
        return isset($args['voting']) && isset($args['answer']);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function addVoting(ArrayObject $args): bool
    {
        $this->store->load();

        return $this->store->add([$args['voting'], $args['answer']]);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function action(ArrayObject $args): bool
    {
        return $this->validateArguments($args) && $this->addVoting($args) && $this->store->save();
    }
}
