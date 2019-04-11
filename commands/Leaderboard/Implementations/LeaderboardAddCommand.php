<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\SoerCommand;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardAddCommand extends SoerCommand
{
    const SUCCESS_MESSAGE = 'Награда добавлена';

    const FAILURE_MESSAGE = 'Не удалось добавить награду';

    /**
     * @var UserModel
     */
    private $users;

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client);
        $this->users = UserModel::getInstance(new LeaderBoardStoreJSONFile());
    }

    /**
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return \React\Promise\ExtendedPromiseInterface
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        try {
            $this->users->incrementReward($args['name']->username, $args['emoji']);
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
