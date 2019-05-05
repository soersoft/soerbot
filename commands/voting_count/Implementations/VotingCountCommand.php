<?php

namespace SoerBot\Commands\Voting_count\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use CharlotteDunois\Yasmin\Models;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Voting\Services\VotingStoreJSONFile;

class VotingCountCommand extends Command
{
   // const SUCCESS_MESSAGE = 'Голосование добавлено';

    //const FAILURE_MESSAGE = 'Не удалось добавить голосование';

    /**
     * @var VotingStoreJSONFile
     */
    private $store;

    /**
     * VotingCountCommand constructor.
     *
     * @param \CharlotteDunois\Livia\LiviaClient $client
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'count', // Give command name
            'aliases' => [],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Подсчет голосований:', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
            'args' => [ ],                  
            ],
        $this->store = new VotingStoreJSONFile());}
    

    /**
     * @param CommandMessage $message
     * @param ArrayObject $args
     * @param bool $fromPattern
     * @return Message|Message[]|ExtendedPromiseInterface|ExtendedPromiseInterface[]|void|null
     */
    public function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
        
        $collectPoll = ($message->message->channel->id);
      
        if (strpos($collectPoll, 'Вопрос:') !== false) {
            return $message->say($collectPoll);
        }
        else return $message->say('hi');
    }};
      

                  