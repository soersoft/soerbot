<?php

namespace SoerBot\Classes;

use CharlotteDunois\Yasmin\Utils\Collection;
use SoerBot\Classes\Interfaces\StoreInterface;

abstract class Feature
{
    /**
     * @var Collection
     */
    protected $users = null;

    /**
     * @var StoreInterface
     */
    protected $store;

    /**
     * UsersModel constructor.
     * @param StoreInterface $store
     */
    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Loads users from the store and creates collection.
     */
    abstract public function load();

    /**
     * Saves users data to the store.
     * @return bool|int
     */
    abstract public function save();

    /**
     * Returns instance of User for particular username.
     * @param $username
     * @return User|null
     */
    public function get($username): ?User
    {
        if (!($this->users instanceof Collection)) {
            return null;
        }

        return $this->users->first(function ($user) use ($username) {
            return $user->getName() === $username;
        });
    }

    /**
     * Alias for get($username)
     * @param string $username
     * @return User|null
     */
    public function with(string $username)
    {
        return $this->get($username);
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
     * @return Collection | null
     */
    public function all(): ?Collection
    {
        return $this->users;
    }
}
