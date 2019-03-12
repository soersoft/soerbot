<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Traits\ArrayServiceMethods;

class User
{
    protected $name;

    /**
     * @var array
     */
    protected $rewards;

    protected $linesDelimiter;

    protected $prefix;

    use ArrayServiceMethods;

    public function __construct($name, array $rewards, $linesDelimiter = PHP_EOL)
    {
        $this->name = $name;
        $this->linesDelimiter = $linesDelimiter;
        $this->rewards = $rewards;
        $this->prefix = null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRewards()
    {
        return $this->rewards;
    }

    public function getReward($rewardName)
    {
        $key = $this->findKey($this->rewards, 'emoji', $rewardName);

        return ($key === false) ? false : $this->rewards[$key];
    }

    public function addReward($rewardName, $rewardCount)
    {
        $newReward = ['emoji' => $rewardName, 'count' => $rewardCount];

        if ($this->exists($this->rewards, 'emoji', $rewardName)) {
            $key = $this->findKey($this->rewards, 'emoji', $rewardName);
            $this->rewards[$key] = $newReward;
        } else {
            $this->rewards[] = $newReward;
        }

        return true;
    }

    public function changeRewardAmount($rewardName, $value)
    {
        if ($reward = $this->getReward($rewardName)) {
            $value += $reward['count'];
        }

        if ($value > 0) {
            return $this->addReward($rewardName, $value);
        } else {
            return $this->removeReward($rewardName);
        }
    }

    public function incrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, 1);
    }

    public function decrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, -1);
    }

    public function removeReward($rewardName)
    {
        if ($this->exists($this->rewards, 'emoji', $rewardName)) {
            $key = $this->findKey($this->rewards, 'emoji', $rewardName);
            unset($this->rewards[$key]);
        }

        return true;
    }

    public function addPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function __toString()
    {
        return ($this->prefix ?? '') . '@' . $this->name . $this->linesDelimiter . $this->makeRewardsAsString();
    }

    public function toString()
    {
        return (string)$this;
    }

    protected function makeRewardsAsString()
    {
        $rewards = '';

        foreach ($this->rewards as $reward) {
            $rewards .= str_repeat($reward['emoji'], $reward['count']) . $this->linesDelimiter;
        }

        return $rewards;
    }
}
