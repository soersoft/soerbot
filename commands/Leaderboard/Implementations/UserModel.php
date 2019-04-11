<?php

namespace SoerBot\Commands\Leaderboard\Implementations;

use SoerBot\Commands\Leaderboard\Traits\ArrayServiceMethods;
use SoerBot\Commands\Leaderboard\Interfaces\UserModelInterface;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;

class UserModel implements UserModelInterface
{
    /**
     * If the leaderboard is empty this message will be printed.
     */
    const LEADERBOARD_IS_EMPTY = 'Пока в таблице лидеров никого нет.';

    /**
     * These emoji will be printed for the first three places.
     */
    const  EMOJI_OF_PRIZE_PLACES = [':one: ', ':two: ', ':three: '];

    /**
     * @var UserModel
     */
    protected static $instance;

    /**
     * @var User[]
     */
    protected $users = [];

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

        if (!empty($users = $this->store->toArray())) {
            foreach ($users as $user) {
                $this->users[] = new User($user['username'], $user['rewards']);
            }
        }
    }

    /**
     * Returns singleton instance.
     *
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
     *
     * @param string $username
     * @param string $rewardName
     * @return void
     */
    public function incrementReward(string $username, $rewardName)
    {
        $username = $this->cleanupUsername($username);

        if (!$user = $this->get($username)) {
            $this->users[] = $user = new User($username, []);
        }

        $user->incrementReward($rewardName);

        $this->store->add([$user->getName(), $user->getRewards()]);
        $this->store->save();
    }

    /**
     * Remove chosen rewards.
     *
     * @param string $username
     * @param string $rewardName
     * @return bool
     */
    public function removeRewardsByType(string $username, $rewardName)
    {
        $username = $this->cleanupUsername($username);

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
     * Sorts user by their rewards' points.
     *
     * @param string $direction Can be "desc" or "asc" value
     * @return $this
     */
    public function sort($direction = 'desc')
    {
        if (empty($this->users)) {
            return $this;
        }

        usort($this->users, function ($a, $b) use ($direction) {
            if (!($a instanceof User) || !($b instanceof User)) {
                return 0;
            }

            if ($a->getPointsAmount() === $b->getPointsAmount()) {
                return 0;
            }

            $result = ($a->getPointsAmount() > $b->getPointsAmount()) ? -1 : 1;

            return ($direction === 'desc') ? $result : $result * -1;
        });

        return $this;
    }

    /**
     * Makes a string from the all user's data.
     *
     * @return string
     */
    public function getLeaderBoardAsString()
    {
        if (empty($this->users)) {
            return self::LEADERBOARD_IS_EMPTY;
        }

        $strLeaderBoard = '';

        foreach ($this->users as $index => $user) {
            if (array_key_exists($index, $places = self::EMOJI_OF_PRIZE_PLACES)) {
                $user->addPrefix($places[$index]);
            }

            $strLeaderBoard .= $user . $this->linesDelimiter;
        }

        return $strLeaderBoard;
    }

    /**
     * Returns user instance for chosen username.
     *
     * @param string $username
     * @return User|null
     */
    protected function get(string $username)
    {
        $username = $this->cleanupUsername($username);

        if (!empty($this->users)) {
            return $this->first($this->users, function ($user) use ($username) {
                return $user->getName() === $username;
            });
        }

        return null;
    }

    /**
     * Remove user from store.
     *
     * @param string $username
     * @return bool
     */
    public function remove(string $username): bool
    {
        $username = $this->cleanupUsername($username);

        $this->store->remove($username);

        return $this->store->save();
    }
    /**
     * Check if user exists.
     *
     * @param string $username
     * @return bool
     */
    public function hasUser(string $username): bool
    {
        $username = $this->cleanupUsername($username);

        if ($this->store->get($username)) {
            return true;
        }

        return false;
    }

    /**
     * Cleanup username from unwanted characters.
     *
     * @param string $username
     * @return string
     */
    private function cleanupUsername(string $username): string
    {
        return ltrim($username, '@');
    }

    /**
     * Singleton cloning is forbidden.
     */
    protected function __clone()
    {
    }
}
