<?php

namespace SoerBot\Types;

use CharlotteDunois\Livia\LiviaClient;
use CharlotteDunois\Livia\CommandMessage;
use CharlotteDunois\Livia\Arguments\Argument;
use CharlotteDunois\Livia\Types\ArgumentType;

class RewardArgumentType extends ArgumentType
{
    /**
     * Rewards values.
     * @var string[]
     */
    protected $rewards = ['⭐', '🏅'];

    public function __construct(LiviaClient $client)
    {
        parent::__construct($client, 'reward');
    }

    public function validate(string $value, CommandMessage $message, ?Argument $arg = null)
    {
        return \in_array($value, $this->rewards);
    }

    public function parse(string $value, CommandMessage $message, ?Argument $arg = null)
    {
        return $value;
    }
}
