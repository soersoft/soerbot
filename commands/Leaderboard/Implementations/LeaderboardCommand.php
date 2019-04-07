<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\SoerCommand;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardCommand extends SoerCommand
{
    /**
     * @var UserModel
     */
    private $users;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client);
        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile(__DIR__ . $this->storeJSONFile));
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message->say($this->users->sort()->getLeaderBoardAsString());
    }
}
