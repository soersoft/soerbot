<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use ArrayObject;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Commands\Command;
use SoerBot\Commands\Leaderboard\Store\LeaderBoardStoreJSONFile;

class LeaderboardRemoveRewardsByTypeCommand extends Command
{
    const SUCCESS_MESSAGE = 'Награды удаленны';

    const FAILURE_MESSAGE = 'Не удалось удалить награды';

    /** @var UserModel */
    private $users;

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
            $this->action($args) ? self::SUCCESS_MESSAGE : self::FAILURE_MESSAGE
        );
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function action(ArrayObject $args): bool
    {
        return $this->validateArguments($args) && $this->removeRewards($args);
    }

    /**
     * @param ArrayObject $args
     * @return bool
     */
    private function validateArguments(ArrayObject $args): bool
    {
        return isset($args['name']) && isset($args['emoji']);
    }

    private function removeRewards(ArrayObject $args): bool
    {
        return $this->users->removeRewardsByType($args['name']->username, $args['emoji']);
    }


}
