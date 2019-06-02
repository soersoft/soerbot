<?php

namespace App\Implementations;

use App\Interfaces\StoreInterface;
use App\Implementations\Features\Feature;
use CharlotteDunois\Yasmin\Utils\Collection;

class UsersModel
{
    /**
     * @var Collection
     */
    protected $users = null;

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        $this->users = new Collection();
    }

    public function addFeature(string $name, $featureClassname, StoreInterface $store)
    {
        $usersData = $store->load();

        foreach ($usersData as $userData) {
            if (!array_key_exists('username', $userData)) {
                continue;
            }

            if (!($user = $this->get($userData['username']))) {
                $user = new User($userData['username']);
                $this->create($user);
            }

            $feature = new $featureClassname($userData['data']);

            if (!($feature instanceof Feature)) {
                throw new \RuntimeException('feature must be an instance of Feature!');
            }

            $user->addFeature($name, $feature);
        }
    }

    /**
     * Saves users data to the store.
     * @return bool|int
     */
    public function save()
    {
        if (!($this->users instanceof Collection)) {
            throw new \RuntimeException('You have to load users collection before saving it.');
        }

        $data = [];

        foreach ($this->users->all() as $user) {
            $data[] = ['username' => $user->getName(), 'rewards' => $user->getRewards()];
        }

        return $this->store->save($data);
    }

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
