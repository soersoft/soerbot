<?php

namespace SoerBot\Commands;

use ArrayObject;
use RuntimeException;
use SoerBot\Services\RankService;
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
     * Message for success response.
     *
     * @var string
     */
    const SUCCESS_MESSAGE = 'У пользователя: %s карма %d';

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
            $responseMessage = sprintf(
                self::SUCCESS_MESSAGE,
                $user = $this->processing($message),
                $this->service()->getRank($user)
            );
        } catch (\Exception $exception) {
            $responseMessage = $exception->getMessage();
        }

        return $this->say($message, $responseMessage);
    }

    /**
     * @param $message
     * @param $responseContent
     * @return mixed
     */
    private function say($message, $responseContent)
    {
        return $message->say('Pinging...')->then(function ($msg) use ($responseContent) {
            return $msg->edit($responseContent);
        });
    }

    /**
     * @param CommandMessage $message
     * @return string
     */
    private function processing(CommandMessage $message)
    {
        $arguments = $this->arguments($message);

        $this->service()->update($arguments['user'], $arguments['rank']);

        return $arguments['user'];
    }

    /**
     * @return RankService
     */
    private function service()
    {
        return new RankService();
    }

    /**
     * @param $arguments
     * @return array
     */
    private function parseArguments($arguments)
    {
        $spliteArguments = explode(' ', $arguments);

        return [
            'user' => $spliteArguments[1],
            'rank' => (int) $spliteArguments[0],
        ];
    }

    /**
     * @param CommandMessage $message
     * @return string|string[]
     */
    private function arguments(CommandMessage $message)
    {
        $arguments = $message->parseCommandArgs();

        if (empty($arguments)) {
            throw new RuntimeException('Параметры отсутствуют');
        }

        return $this->parseArguments($arguments);
    }
}
