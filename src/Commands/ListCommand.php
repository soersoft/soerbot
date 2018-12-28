<?php


namespace SoerBot\Commands;


use SoerBot\Services\ListService;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;
use SoerBot\Transformers\UserListsToTextTransformer;

class ListCommand extends Command
{
    /**
     * Конфигурации команды.
     *
     * @var array
     */
    private $config = [
        'name' => 'list',
        'aliases' => [],
        'group' => 'commands',
        'description' => 'Выводит сводную информацию о соятоянии пользователей.',
        'guildOnly' => false,
        'throttling' => [
            'usages' => 5,
            'duration' => 10,
        ],
        'guarded' => true,
    ];

    public function __construct(LiviaClient $client, array $info)
    {
        parent::__construct($client, $this->config);
    }

    /**
     * Runs the command.
     *
     * @param CommandMessage $message
     * @param \ArrayObject $args
     * @param bool $fromPattern
     * @return ExtendedPromiseInterface|ExtendedPromiseInterface[]|Message|Message[]|null|void
     */
    public function run(CommandMessage $message, \ArrayObject $args, bool $fromPattern)
    {
        return $message
            ->say('Подготовка списка...')
            ->then(function ($message) {
                $response = $this->transformer()
                    ->transform($this->service()->userList());

                return $message->edit($response);
            });
    }

    /**
     * @return ListService
     */
    private function service()
    {
        return new ListService();
    }

    /**
     * @return UserListsToTextTransformer
     */
    private function transformer()
    {
        return new UserListsToTextTransformer();
    }
}
