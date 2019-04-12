<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardRemoveRewardsByTypeCommand extends Command
{
    const SUCCESS_MESSAGE = 'Награды удалены';

    const FAILURE_MESSAGE = 'Не удалось удалить награды';

    /** @var UserModel */
    private $users;

    private $allowedRoles = ['product owner', 'куратор'];

    protected $config = [
      'name' => 'leaderboard-remove-rewards', // Give command name
      'aliases' => [''],
      'group' => 'utils', // Group in ['command', 'util']
      'description' => 'Удаляет все награды указанного типа у участника',
      'guildOnly' => false,
      'throttling' => [
        'usages' => 5,
        'duration' => 10,
      ],
      'guarded' => true,
      'args' => [ // If you need some variables you should either fill this section or remove it
        [
          'key' => 'name',
          'label' => 'name',
          'prompt' => 'Введите имя пользователя',
          'type' => 'user',
        ],
        [
          'key' => 'emoji',
          'label' => 'emoji',
          'prompt' => 'Какую награду удалить?',
          'type' => 'reward',
        ],
      ],
    ];

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, $this->config);

        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile());
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return ExtendedPromiseInterface|null
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        if (!$this->users->removeRewardsByType($args['name']->username, $args['emoji'])) {
            return $message->say('Не удалось удалить награды пользователя ' . $user . '');
        }

        return $message->say('Награды удалены');
    }

    /**
     * Checks if the user has permission to use the command.
     *
     * @param \CharlotteDunois\Livia\CommandMessage $message
     * @param bool $ownerOverride Whether the bot owner(s) will always have permission.
     * @return bool|string  Whether the user has permission, or an error message to respond with if they don't.
     */
    public function hasPermission(CommandMessage $message, bool $ownerOverride = true)
    {
        $hasPermission = parent::hasPermission($message, $ownerOverride);
        if ($hasPermission === true) {
            $hasPermission = $this->hasAllowedRole($message);
        }

        return $hasPermission;
    }

    /**
     * @param CommandMessage $message
     * @return bool
     */
    public function hasAllowedRole(CommandMessage $message)
    {
        if (!empty($this->allowedRoles) && $roles = $message->member->roles) {
            foreach ($roles as $role) {
                $name = mb_strtolower($role->name);
                if (in_array($name, $this->allowedRoles, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
