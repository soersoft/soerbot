<?php
namespace SoerBot\Commands\Voting\VotingActor;

use SoerBot\Commands\Voting\Interfaces\VotingStoreInterface;
class VotingActor implements VotingStoreInterface
{
    /**
     * @var votingModel
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client){}
    /**
     *
     * @param $message
     * @return boolean;
     */
    /**
     * 
     * @param $message
     * @return void
     */
    public function voting (CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
    
        try {
            $this->user->incrementVotingUser($message->author->username);
        } catch (InvalidUserNameException $error) {
            $this->client->emit('debug', $error->getMessage());
        }

}}
