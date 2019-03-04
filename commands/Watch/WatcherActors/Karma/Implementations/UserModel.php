<?php

namespace SoerBot\Commands\Watch\WatcherActors\Karma\Implementations;

use SoerBot\Commands\Watch\WatcherActors\Karma\Interfaces\UserModelInterface;

class UserModel implements UserModelInterface
{
    /**
     * @var KarmaStoreJSONFile
     */
    private $store;

    public function __construct()
    {
        $this->store = new KarmaStoreJSONFile();
        $this->load();
    }

    public function load()
    {
        $this->store->load();
    }

    public function save()
    {
        return $this->store->save();
    }

    public function getUserKarma(string $userName): int
    {
        $user = $this->store->get($userName);

        if (!empty($user)) {
            return $user['karma'];
        }

        return 0;
    }

    public function setUserKarma(string $userName): bool
    {
        $karma = $this->getUserKarma($userName);
        $karma += 1;

        return $this->store->add(['name' => $userName, 'karma' => $karma]);
    }
}
