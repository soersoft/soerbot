<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use CharlotteDunois\Yasmin\Utils\Collection;

class User
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Collection
     */
    protected $rewards;

    /**
     * Determines how many points gives each reward.
     * @var array
     */
    protected static $rewardsPoints = [
      '⭐' => 1,
      '🏅' => 5,
    ];

    /**
     * User constructor.
     * @param $name
     * @param array $rewards
     */
    public function __construct($name, array $rewards)
    {
        $this->name = $name;
        $this->rewards = new Collection($this->validateRewards($rewards));
    }

    /**
     * Returns name of the user.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns rewards of the user.
     * @return array
     */
    public function getRewards()
    {
        return array_values($this->rewards->all());
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
                $this->validateReward($reward) && $validRewards[] = $reward;
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
     * @param $rewardName
     * @return array|null
     */
    public function getReward($rewardName)
    {
        return $this->rewards->first(function ($reward) use ($rewardName) {
            return $reward['emoji'] === $rewardName;
        });
    }

    /**
     * Updates reward if it exists or creates the new one if it doesn't exist.
     *
     * @param $rewardName
     * @param $rewardCount
     */
    public function addReward($rewardName, $rewardCount)
    {
        $key = ($reward = $this->getReward($rewardName)) ? $this->rewards->indexOf($reward) : $this->rewards->count();
        $this->rewards->set($key, ['emoji' => $rewardName, 'count' => $rewardCount]);
    }

    /**
     * Removes reward if the user has it.
     * @param $rewardName
     */
    public function removeReward($rewardName)
    {
        if (($reward = $this->getReward($rewardName))) {
            $key = $this->rewards->indexOf($reward);
            if ($key >= 0) {
                $this->rewards->delete($key);
            }
        }
    }

    /**
     * Changes reward amount, can take a positive or negative number. Removes reward if its count less then one.
     * @param $rewardName
     * @param $value
     */
    public function changeRewardAmount($rewardName, $value)
    {
        if (($reward = $this->getReward($rewardName))) {
            $key = $this->rewards->indexOf($reward);
            if ($key >= 0) {
                $value += $this->rewards->get($key)['count'];
            }
        }

        $value > 0 ? $this->addReward($rewardName, $value) : $this->removeReward($rewardName);
    }

    /**
     * Increments reward amount by 1.
     * @param $rewardName
     */
    public function incrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, 1);
    }

    /**
     * Decrements reward amount by 1.
     * @param $rewardName
     */
    public function decrementReward($rewardName)
    {
        $this->changeRewardAmount($rewardName, -1);
    }

    /**
     * Returns a string which contains the username and his rewards.
     * @param string $linesDelimiter
     * @param null $prefix
     * @return string
     */
    public function toString($linesDelimiter = PHP_EOL, $prefix = null)
    {
        return ($prefix ?? '') . $this->name . $linesDelimiter . $this->makeRewardsAsString($linesDelimiter);
    }

    /**
     * Returns a string which contains all the rewards of the user.
     * @param $linesDelimiter
     * @return string
     */
    protected function makeRewardsAsString($linesDelimiter)
    {
        $rewards = '';

        foreach ($this->rewards->all() as $reward) {
            $rewards .= str_repeat($reward['emoji'], $reward['count']) . $linesDelimiter;
        }

        return $rewards;
    }

    /**
     * Returns user's total rewards points. If points for current reward don't define function adds 0 for this reward.
     * @return int
     */
    public function getPointsAmount()
    {
        if ($this->rewards->count() === 0) {
            return 0;
        }

        return (function ($rewardsPoints) {
            return array_reduce($this->rewards->all(), function ($amount, $reward) use ($rewardsPoints) {
                $points = array_key_exists($reward['emoji'], $rewardsPoints) ? $rewardsPoints[$reward['emoji']] : 0;

                return $amount += $reward['count'] * $points;
            });
        })(self::$rewardsPoints);
    }
}
