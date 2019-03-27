<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardAddCommand extends Command
{
    const SUCCESS_MESSAGE = 'Награда добавлена';

    const FAILURE_MESSAGE = 'Не удалось добавить награду';

    /**
     * @var UserModel
     */
    private $users;

    public $allowRoles;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
          'name' => 'leaderboard-add', // Give command name
          'aliases' => [''],
          'group' => 'utils', // Group in ['command', 'util']
          'description' => 'Добавляет награду участнику',
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
              'prompt' => 'Какую награду добавить?',
              'type' => 'string',
            ],
          ],
        ]);

        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile(realpath(__DIR__ . '/../Store/leaderboard.json')));

        $this->allowRoles = [
          'product owner', 'куратор',
        ];
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
            $this->action($args) ? self::SUCCESS_MESSAGE : self::FAILURE_MESSAGE
        );
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function action(ArrayObject $args): bool
    {
        return $this->validateArguments($args) && $this->addReward($args);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function validateArguments(ArrayObject $args): bool
    {
        return isset($args['name']) && isset($args['emoji']);
    }

    private function addReward(ArrayObject $args): bool
    {
        return $this->users->incrementReward($args['name']->username, $args['emoji']);
    }

    /**
     * Checks if the user has permission to use the command.
     * @param \CharlotteDunois\Livia\CommandMessage  $message
     * @param bool                                   $ownerOverride  Whether the bot owner(s) will always have permission.
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
