<?php

namespace SoerBot\Classes\Features;

use SoerBot\Classes\Feature;
use CharlotteDunois\Yasmin\Utils\Collection;

class RewardsFeature extends Feature
{
    /**
     * Loads users from the store and creates collection.
     */
    public function load()
    {
        $data = [];

        foreach ($this->store->load() as $userdata) {
            $user = new User($userdata['username']);
            $user->setRewards($userdata['rewards']);

            $data[] = $user;
        }

        $this->users = new Collection($data);
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
}
