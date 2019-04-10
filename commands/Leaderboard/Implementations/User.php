<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Traits\ArrayServiceMethods;

class User
{
    /**
     * The name of the user.
     * @var string
     */
    protected $name;

    /**
     * The array of user's rewards.
     * @var array
     */
    protected $rewards = [];

    /**
     * Line delimiter for separating name and rewards in stringify functions.
     * @var string
     */
    protected $linesDelimiter;

    /**
     * Useful thing for adding something before username in stringify functions.
     * @var string
     */
    protected $prefix;

    /**
     * Determines how many points gives each reward.
     * @var array
     */
    protected static $rewardsPoints = [
      '⭐' => 1,
      '🏅' => 5,
    ];

    use ArrayServiceMethods;

    public function __construct($name, array $rewards, $linesDelimiter = PHP_EOL)
    {
        $this->rewards = $this->validateRewards($rewards);
        $this->name = $name;
        $this->linesDelimiter = $linesDelimiter;
        $this->prefix = '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * Validates that the array of rewards contains right keys.
     *
     * @param array $rewards
     * @return array
     */
    protected function validateRewards(array $rewards)
    {
        $validRewards = [];

        if (!empty($rewards)) {
            foreach ($rewards as $reward) {
                if ($this->validateReward($reward)) {
                    $validRewards[] = $reward;
                }
            }
        }

        return $validRewards;
    }

    /**
     * Validates that the reward contains right keys.
     *
     * @param array $reward
     * @return bool
     */
    protected function validateReward(array $reward)
    {
        return array_key_exists('emoji', $reward) && array_key_exists('count', $reward);
    }

    /**
     * Returns array which contains name of the reward and its count.
     *
     * @param string $rewardName
     * @return bool|array
     */
    public function getReward($rewardName)
    {
        $key = $this->findKey($this->rewards, 'emoji', $rewardName);

        return ($key === false) ? false : $this->rewards[$key];
    }

    /**
     * Updates reward if it exists or creates the new one if it doesn't exist.
     *
     * @param string $rewardName
     * @param int $rewardCount
     * @return void
     */
    public function addReward($rewardName, $rewardCount)
    {
        $newReward = ['emoji' => $rewardName, 'count' => $rewardCount];

        if ($this->exists($this->rewards, 'emoji', $rewardName)) {
            $key = $this->findKey($this->rewards, 'emoji', $rewardName);
            $this->rewards[$key] = $newReward;
        } else {
            $this->rewards[] = $newReward;
        }
    }

    /**
     * Removes reward if the user has it.
     *
     * @param string $rewardName
     * @return void
     */
    public function removeReward($rewardName)
    {
        if ($this->exists($this->rewards, 'emoji', $rewardName)) {
            $key = $this->findKey($this->rewards, 'emoji', $rewardName);
            unset($this->rewards[$key]);
        }
    }

    /**
     * Changes reward amount, can take a positive or negative number. Removes reward if its count less then one.
     *
     * @param string $rewardName
     * @param int $value
     * @return void
     */
    public function changeRewardAmount($rewardName, $value)
    {
        if ($reward = $this->getReward($rewardName)) {
            $value += $reward['count'];
        }

        $value > 0 ? $this->addReward($rewardName, $value) : $this->removeReward($rewardName);
    }

    /**
     * @param string $rewardName
     * @return void
     */
    public function incrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, 1);
    }

    /**
     * @param string $rewardName
     * @return void
     */
    public function decrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, -1);
    }

    /**
     * @param string $prefix
     */
    public function addPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Returns a string which contains the username and his rewards.
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->prefix ?? '') . $this->name . $this->linesDelimiter . $this->makeRewardsAsString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this;
    }

    /**
     * Returns user's total rewards points. If points for current reward don't define function adds 0 for this reward.
     *
     * @return int
     */
    public function getPointsAmount()
    {
        return (function ($rewardsPoints) {
            return array_reduce($this->rewards, function ($amount, $reward) use ($rewardsPoints) {
                $points = array_key_exists($reward['emoji'], $rewardsPoints) ? $rewardsPoints[$reward['emoji']] : 0;

                return $amount += $reward['count'] * $points;
            });
        })(self::$rewardsPoints);
    }

    /**
     * Returns a string which contains all the rewards of the user.
     * @return string
     */
    protected function makeRewardsAsString()
    {
        $rewards = '';

        foreach ($this->rewards as $reward) {
            $rewards .= str_repeat($reward['emoji'], $reward['count']) . $this->linesDelimiter;
        }

        return $rewards;
    }
}
