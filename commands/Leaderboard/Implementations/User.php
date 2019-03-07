<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

class User
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Reward[]
     */
    protected $rewards;

    /**
     * @var string
     */
    protected $linesDelimiter;

    public function __construct($name, array $rewards, $linesDelimiter = PHP_EOL)
    {
        $this->name = $name;
        $this->linesDelimiter = $linesDelimiter;

        foreach ($rewards as $reward) {
            $this->rewards[] = new Reward($reward['emoji'], $reward['count']);
        }
    }

    public function __toString()
    {
        $rewards = '';

        foreach ($this->rewards as $reward) {
            $rewards .= $reward . $this->linesDelimiter;
        }

        return '@' . $this->name . $this->linesDelimiter . $rewards;
    }
}
