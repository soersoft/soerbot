<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Configurator;
use SoerBot\Commands\Command;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardCommand extends Command
{
    /**
     * @var UserModel
     */
    private $users;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client);
        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile($this->storeJSONFile));
        var_dump(Configurator::get('leaderboard')['storeJSONFile']);
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
