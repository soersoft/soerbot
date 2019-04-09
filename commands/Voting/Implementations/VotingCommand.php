<?php

namespace SoerBot\Commands\Voting\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Voting\Services\VotingStoreJSONFile;

class VotingCommand extends Command
{
   // const SUCCESS_MESSAGE = 'Голосование добавлено';

    //const FAILURE_MESSAGE = 'Не удалось добавить голосование';

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
                    'key' => 'question',
                    'label' => 'question',
                    'prompt' => 'Введите голосование в таком виде ($voting № question?|answer1|answer2 ...):',
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
       // $question = '***Вопрос:***'.\PHP_EOL;  
                $poll = explode("|", $message->message); 
                foreach ($poll as $value) {                  
                $answer .=$value.\PHP_EOL;  
                }
        { 
            return  $message->say("***".$answer."***") ; 
        }
    }        
}