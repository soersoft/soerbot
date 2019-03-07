<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

class Reward
{
    /**
     * @var string
     */
    protected $emoji;

    /**
     * @var int
     */
    protected $count;

    public function __construct($emoji, $count)
    {
        $this->emoji = $emoji;
        $this->count = $count;
    }

    public function __toString()
    {
        return str_repeat($this->emoji, $this->count);
    }
}
