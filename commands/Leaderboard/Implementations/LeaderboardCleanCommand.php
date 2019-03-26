<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardCleanCommand extends Command
{
    const SUCCESS_MESSAGE = 'Пользователи без наград удалены из списка.';

    const FAILURE_MESSAGE = 'Что-то пошло не так. Чистка пользователей без наград завершилась неудачей.';

    /**
     * @var UserModel
     */
    private $users;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
          'name' => 'leaderboard-clean', // Give command name
          'aliases' => [''],
          'group' => 'utils', // Group in ['command', 'util']
          'description' => 'Удаляет из Leaderboard пользователей без наград',
          'guildOnly' => false,
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
          'guarded' => true,
          'args' => [],
        ]);

        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile(realpath(__DIR__ . '/../Store/leaderboard.json')));
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message->say(
            $this->action() ? self::SUCCESS_MESSAGE : self::FAILURE_MESSAGE
        );
    }

    /**
     * @return bool
     */
    private function action(): bool
    {
        return $this->users->cleanReward();
    }
}