<?php

namespace SoerBot\Commands\Leaderboard\AdvImplementations;

use CharlotteDunois\Yasmin\Utils\Collection;

class UsersModel
{
    /**
     * @var Collection
     */
    protected $users = null;

    /**
     * @var LeaderBoardStoreInterface
     */
    protected $store;

    /**
     * UsersModel constructor.
     * @param LeaderBoardStoreInterface $store
     */
    public function __construct(LeaderBoardStoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Loads users from the store and creates collection.
     */
    public function load()
    {
        $data = [];

        foreach ($this->store->load() as $user) {
            $data[] = new User($user['username'], $user['rewards']);
        }

        $this->users = new Collection($data);
    }

    /**
     * Saves users data to the store.
     */
    public function save()
    {
        if (!($this->users instanceof Collection)) {
            $this->load();
        }

        $data = [];

        foreach ($this->users->all() as $user) {
            $data[] = ['username' => $user->getName(), 'rewards' => $user->getRewards()];
        }

        $this->store->save($data);
    }

    /**
     * Returns instance of User for particular username.
     * @param $username
     * @return User|null
     */
    public function get($username): ?User
    {
        return $this->users->first(function ($user) use ($username) {
            return $user->getName() === $username;
        });
    }

    /**
     * Deletes user from collection.
     * @param User $user
     */
    public function delete(User $user)
    {
        $key = $this->users->indexOf($user);

        if ($key >= 0) {
            $this->users->delete($key);
            $this->save();
        }
    }

    /**
     * Creates user.
     * @param User $user
     */
    public function create(User $user)
    {
        $this->users->set($this->users->count(), $user);
        $this->save();
    }

    /**
     * Updates user data.
     * @param User $oldUser
     * @param User $newUser
     */
    public function update(User $oldUser, User $newUser)
    {
        $key = $this->users->indexOf($oldUser);

        if ($key >= 0) {
            $this->users->set($key, $newUser);
            $this->save();
        }
    }

    /**
     * Returns instance of users collection.
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->users;
    }
}
