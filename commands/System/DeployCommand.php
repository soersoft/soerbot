<?php

namespace SoerBot\Commands\System;

use Throwable;
use ArrayObject;
use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Yasmin\Models\Message;
use CharlotteDunois\Livia\Commands\Command;
use React\Promise\ExtendedPromiseInterface;

class DeployCommand extends Command
{
    /**
     * DeployCommand constructor.
     *
     * @param LiviaClient $client
     */
    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, [
            'name' => 'deploy',
            'aliases' => [],
            'group' => 'utils',
            'description' => 'Deploy command',
            'guildOnly' => false,
            'ownerOnly' => true,
            'throttling' => [
                'usages' => 5,
                'duration' => 10,
            ],
            'guarded' => true,
        ]);
    }

    /**
     * @param CommandMessage $message
     * @param ArrayObject $args
     * @param bool $fromPattern
     * @throws Throwable
     * @return Message|Message[]|ExtendedPromiseInterface|ExtendedPromiseInterface[]|void|null
     */
    public function run(CommandMessage $message, ArrayObject $args, bool $fromPattern)
    {
        $this->client->emit('stop');
    }
}
