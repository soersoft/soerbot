<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardCommand extends Command
{
    private $users;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
          'name' => 'leaderboard', // Give command name
          'aliases' => [''],
          'group' => 'utils', // Group in ['command', 'util']
          'description' => 'Выводит таблицу участников и набранные очки',
          'guildOnly' => false,
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
          'guarded' => true,
          'args' => [],
        ]);

        $this->users = new UserModel(new LeaderBoardStoreJSONFile('/../Store/leaderboard.json'));
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message->say($this->users->getLeaderBoardAsString());
    }
}
