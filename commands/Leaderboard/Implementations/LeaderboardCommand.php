<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;

class LeaderboardCommand extends Command
{
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
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        $filePath = realpath(__DIR__ . '/../../../tests/Fixtures/leaderboard.tmp.txt');
        $leaderboardStaticMessage = file_get_contents($filePath);

        return $message->say($leaderboardStaticMessage);
    }
}
