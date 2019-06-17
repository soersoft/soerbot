<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Classes\User;
use SoerBot\Classes\UsersModel;
use SoerBot\Classes\StoreJSONFile;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Leaderboard\Features\RewardsFeature;

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
        $store = new StoreJSONFile(__DIR__ . '/../Store/leaderboard.json');
        $feature = new RewardsFeature($store);

        $users = new UsersModel();

        $users->addFeature('rewards', $feature);
        $users->feature('rewards')->load();

        $leaderboard = $users
          ->feature('rewards')
          ->all()
          ->sort(function (User $a, User $b) {
              return $b->getPointsAmount() - $a->getPointsAmount();
          })
          ->map(function (User $user) {
              return $user->toString();
          });

        $leaderboard = $leaderboard->all();

        $i = 0;

        while (array_key_exists($i, $leaderboard) && array_key_exists($i, self::EMOJI_OF_PRIZE_PLACES)) {
            $leaderboard[$i] = self::EMOJI_OF_PRIZE_PLACES[$i] . $leaderboard[$i];
            $i++;
        }

        return $message->say(implode($leaderboard));
    }
}
