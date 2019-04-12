<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;
use SoerBot\Commands\Leaderboard\Exceptions\LeaderboardException;

class LeaderboardRemoveUser extends Command
{
    /**
     * @var UserModel
     */
    private $users;

    /**
     * @var array
     */
    private $allowedRoles = ['product owner', 'куратор'];

    /**
     * LeaderboardRemoveUser constructor.
     *
     * @param \CharlotteDunois\Livia\LiviaClient $client
     * @throws LeaderboardException
     */
    public function __construct(\CharlotteDunois\Livia\LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'leaderboard-remove-user', // Give command name
            'aliases' => ['leaderboard-delete-user'],
            'group' => 'utils', // Group in ['command', 'util']
            'description' => 'Удаляет участника из списка', // Fill the description
            'guildOnly' => false,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'args' => [
                [
                    'key' => 'name',
                    'label' => 'name',
                    'prompt' => 'Введите имя пользователя',
                    'type' => 'string',
                ],
            ],
        ]);

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
        $user = trim(@$args['name']);

        if (empty($user)) {
            return $message->say('Введите имя пользователя для удаления');
        }

        if (!$this->users->hasUser($user)) {
            return $message->say('Пользователь ' . $user . ' не существует');
        }

        if (!$this->users->remove($user)) {
            return $message->say('Не удалось удалить пользователя ' . $user . '');
        }

        return $message->say('Пользователь ' . $user . ' успешно удален');
    }

    /**
     * Checks if the user has permission to use the command.
     *
     * @param \CharlotteDunois\Livia\CommandMessage  $message
     * @param bool                                   $ownerOverride  Whether the bot owner(s) will always have permission.
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
