<?php

namespace SoerBot\Commands;

use ArrayObject;
use RuntimeException;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;

/**
 * Class Up.
 * @package SoerBot\Commands\Up
 */
class Up extends Command
{
    /**
     * Конфигурации команды.
     *
     * @var array
     */
    private $config = [
        'name' => 'up',
        'aliases' => [],
        'group' => 'commands',
        'description' => 'Увеличивает рейтинг пользователя.',
        'guildOnly' => false,
        'throttling' => [
            'usages' => 5,
            'duration' => 10,
        ],
        'guarded' => true,
    ];

    protected $rank = [];

    public function __construct(\CharlotteDunois\Livia\LiviaClient $client, array $info)
    {
        parent::__construct($client, $this->config);
    }

    /**
     * Runs the command. The method must return null, an array of Message instances or an instance of Message, a Promise that resolves to an instance of Message, or an array of Message instances. The array can contain Promises which each resolves to an instance of Message.
     * @param CommandMessage $message The message the command is being run for.
     * @param ArrayObject $args The arguments for the command, or the matches from a pattern. If args is specified on the command, thise will be the argument values object. If argsType is single, then only one string will be passed. If multiple, an array of strings will be passed. When fromPattern is true, this is the matches array from the pattern match.
     * @param bool $fromPattern Whether or not the command is being run from a pattern match.
     * @return ExtendedPromiseInterface|ExtendedPromiseInterface[]|Message|Message[]|null|void
     */
    public function run(CommandMessage $message, ArrayObject $args, bool $fromPattern)
    {
        try {
            $user = $this->processing($message);

            return $message->say('Pinging...')->then(function ($msg) use ($user) {
                return $msg->edit(
                    "У пользователя: {$user} карма {$this->rank[$user]}"
                );
            });
        } catch (\Exception $exception) {
            return $message->say('Pinging...')->then(function ($msg) use ($exception) {
                return $msg->edit($exception->getMessage());
            });
        }
    }

    /**
     * @param CommandMessage $message
     * @return string
     */
    public function processing(CommandMessage $message)
    {
        $arguments = $message->parseCommandArgs();

        if (empty($arguments)) {
            throw new RuntimeException('Параметры отсутствуют');
        }

        list($user, $rankAdd) = $this->parseArguments($arguments);

        $this->updateRank($user, $rankAdd);

        return $user;
    }

    /**
     * @param $user
     * @param $rankAdd
     */
    public function updateRank($user, $rankAdd)
    {
        $this->rank[$user] = key_exists($user, $this->rank)
            ? $this->rank[$user] + $rankAdd
            : $rankAdd;
    }

    /**
     * @param $arguments
     * @return array
     */
    public function parseArguments($arguments)
    {
        $spliteArguments = explode(' ', $arguments);

        return [
            $spliteArguments[1],
            (int) $spliteArguments[0],
        ];
    }
}
