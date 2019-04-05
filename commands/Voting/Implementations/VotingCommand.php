<?php

namespace SoerBot\Commands\Voting\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
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
            'group' => 'utils', // Group in ['command', 'util']
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
                    'prompt' => 'Введите название голосования:',
                    'type' => 'string',
                ],
                [
                    'key' => 'answer',
                    'label' => 'answer',
                    'prompt' => 'вариант ответа №1:',
                    'type' => 'string',
                ],
                
            ],
        ]);

        $this->store = new VotingStoreJSONFile();
        $this->VotingActor = $this->createNewVotingActor($client);
        $client->emit('RegisterVoting', $this->VotingActor);
    }

    /**
     * @param CommandMessage $message
     * @param ArrayObject $args
     * @param bool $fromPattern
     * @return Message|Message[]|ExtendedPromiseInterface|ExtendedPromiseInterface[]|void|null
     */
    private function createNewVotingActor($client)
    {
        return new VotingActor($client);
    }
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $votingUser = $message->author->username;
        $votingModel = $this->VotingActor->getUser();
        $voting = $votingModel->getUserVotinh($votingUser);
        return $message->reply("Ваше голосование: $voting");
    }
}
