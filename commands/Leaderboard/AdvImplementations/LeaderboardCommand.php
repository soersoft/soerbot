<?php

namespace SoerBot\Commands\Leaderboard\AdvImplementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;

class LeaderboardCommand extends Command
{
    const LEADERBOARD_IS_EMPTY = 'Пока в таблице лидеров никого нет.';
    const EMOJI_OF_PRIZE_PLACES = [':one: ', ':two: ', ':three: '];

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
          'name' => 'leaderboard', // Give command name
          'group' => 'utils', // Group in ['command', 'util']
          'description' => 'Выводит таблицу участников и набранные очки', // Fill the description
          'guildOnly' => false,
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
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
        $users = new UsersModel(new LeaderBoardStoreJSONFile(__DIR__ . '/../Store/leaderboard.json'));
        $users->load();

        if ($users->all()->count() === 0) {
            return $message->say(self::LEADERBOARD_IS_EMPTY);
        }

        $leaderboard = $users
          ->all()
          ->sort(function (User $a, User $b) {
              return $b->getPointsAmount() - $a->getPointsAmount();
          })
          ->map(function (User $user) {
              return $user->toString();
          });

        $leaderboard = $leaderboard->all();

        $i = 0;

        while (self::EMOJI_OF_PRIZE_PLACES[$i] && $leaderboard[$i]) {
            $leaderboard[$i] = self::EMOJI_OF_PRIZE_PLACES[$i] . $leaderboard[$i];
            $i++;
        }

        return $message->say(implode($leaderboard));
    }
}
