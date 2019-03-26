<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Traits\ArrayServiceMethods;
use SoerBot\Commands\Leaderboard\Interfaces\UserModelInterface;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

class UserModel implements UserModelInterface
{
    /**
     * @var UserModel
     */
    protected static $instance;

    /**
     * @var User[]
     */
    protected $users;

    /**
     * @var LeaderBoardStoreInterface
     */
    protected $store;

    /**
     * Line delimiter for separating users in stringify functions.
     * @var string
     */
    protected $linesDelimiter;

    use ArrayServiceMethods;

    private function __construct(LeaderBoardStoreInterface $store, $linesDelimiter)
    {
        $this->linesDelimiter = $linesDelimiter;
        $this->store = $store;

        $this->store->load();

        foreach ($this->store->toArray() as $user) {
            $this->users[] = new User($user['username'], $user['rewards']);
        }
    }

    /**
     * Returns singleton instance.
     * @param LeaderBoardStoreInterface $store
     * @param string $linesDelimiter
     * @return UserModel
     */
    public static function getInstance(LeaderBoardStoreInterface $store, $linesDelimiter = PHP_EOL)
    {
        if (self::$instance === null) {
            self::$instance = new UserModel($store, $linesDelimiter);
        }

        return self::$instance;
    }

    /**
     * Increments chosen reward and saves the result in the store.
     * @param string $username
     * @param string $rewardName
     * @return bool
     */
    public function incrementReward($username, $rewardName)
    {
        if (!$user = $this->get($username)) {
            $this->users[] = $user = new User($username, []);
        }

        $user->incrementReward($rewardName);

        $this->store->add([$user->getName(), $user->getRewards()]);
        $this->store->save();

        return true;
    }

    /**
     * Sorts user by their rewards' points.
     * @param string (desc|asc)
     * @return $this
     */
    public function sort($direction = 'desc')
    {
        usort($this->users, function ($a, $b) use ($direction) {
            if ($a->getPointsAmount() == $b->getPointsAmount()) {
                return 0;
            }

            $result = ($a->getPointsAmount() > $b->getPointsAmount()) ? -1 : 1;

            return ($direction == 'desc') ? $result : $result * -1;
        });

        return $this;
    }

    /**
     * Remove chosen rewards.
     * @param string $username
     * @param string $rewardName
     * @return bool
     */
    public function removeRewardsByType($username, $rewardName)
    {
        if (!$user = $this->get($username)) {
            return false;
        }

        if (empty($user->getReward($rewardName))) {
            return false;
        }

        $user->removeReward($rewardName);

        return true;
    }

    /**
     * Makes a string from the all user's data.
     * @return string
     */
    public function getLeaderBoardAsString()
    {
        $str = '';

        foreach ($this->users as $index => $user) {
            if (array_key_exists($index, $places = [':one: ', ':two: ', ':three: '])) {
                $user->addPrefix($places[$index]);
            }

            $str .= $user . $this->linesDelimiter;
        }

        return $str;
    }

    /**
     * Singleton cloning is forbidden.
     */
    protected function __clone()
    {
    }

    /**
     * Returns user instance for chosen username.
     * @param $username
     * @return User
     */
    protected function get($username)
    {
        return $this->first($this->users, function ($user) use ($username) {
            return $user->getName() === $username;
        });
    }
}
