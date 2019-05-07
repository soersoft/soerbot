<?php

namespace SoerBot\Commands\Leaderboard\AdvImplementations;

use CharlotteDunois\Livia\Commands\Command;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;

class LeaderboardAddCommand extends Command
{
    const SUCCESS_MESSAGE = 'Награда добавлена';
    const FAILURE_MESSAGE = 'Не удалось добавить награду';

    protected $allowRoles = [
      'product owner',
      'куратор'
    ];

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
          'name' => 'leaderboard-add', // Give command name
          'group' => 'utils', // Group in ['command', 'util']
          'description' => 'Добавляет награду участнику', // Fill the description
          'guildOnly' => false,
          'throttling' => [
            'usages' => 5,
            'duration' => 10,
          ],
          'args' => [
            [
              'key' => 'name',
              'name' => 'name',
              'prompt' => 'Введите имя пользователя',
              'type' => 'user'
            ],
            [
              'key' => 'emoji',
              'name' => 'emoji',
              'prompt' => 'Какую награду добавить?',
              'type' => 'reward'
            ],
          ],
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

        try {
            $users->get($args['name']->username)->incrementReward($args['emoji']);
            $users->save();
        } catch (\Exception $e) {
            return $message->say(self::FAILURE_MESSAGE);
        }

        return $message->say(self::SUCCESS_MESSAGE);
    }

    /**
     * Checks if the user has permission to use the command.
     * @param \CharlotteDunois\Livia\CommandMessage $message
     * @param bool $ownerOverride Whether the bot owner(s) will always have permission.
     * @return bool|string  Whether the user has permission, or an error message to respond with if they don't.
     */
    public function hasPermission(\CharlotteDunois\Livia\CommandMessage $message, bool $ownerOverride = true)
    {
        $hasPermission = parent::hasPermission($message, $ownerOverride);
        if ($hasPermission === true) {
            $hasPermission = $this->hasAllowedRole($message);
        }

        return $hasPermission;
    }

    public function hasAllowedRole(\CharlotteDunois\Livia\CommandMessage $message)
    {
        if (count($this->allowRoles) > 0) {
            $allow = false;
            $roles = $message->member->roles;
            foreach ($roles as $role) {
                $roleName = mb_strtolower($role->name);
                if (in_array($roleName, $this->allowRoles)) {
                    $allow = true;
                }
            }

            return $allow;
        }
    }
}
