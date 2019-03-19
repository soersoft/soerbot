<?php

namespace SoerBot\Commands\Karma\WatcherActor\Implementations;

use SoerBot\Commands\Karma\WatcherActor\AbstractClasses\AbstractUserModel;
use SoerBot\Commands\Karma\WatcherActor\Exceptions\InvalidUserNameException;
use SoerBot\Commands\Karma\WatcherActor\Exceptions\StoreFileNotFoundException;

class UserModel extends AbstractUserModel
{
    /**
     * @var KarmaStoreJSONFile
     */
    private $store;

    private const KARMA_EMPTY = 0;

    private const KARMA_ONE_STEP = 1;

    public function __construct()
    {
        $this->store = new KarmaStoreJSONFile();
        $this->load();
    }

    protected function load()
    {
        try {
            $this->store->load();
        } catch (StoreFileNotFoundException $error) {
            exit($error->getMessage());
        }
    }

    protected function save()
    {
        $this->store->save();
    }

    private function getUserKarma(string $userName): int
    {
        if (!$this->validateUserName($userName)) {
            throw new InvalidUserNameException('Invalid username. Username must be a string');
        }

        $user = $this->store->get($userName);

        if (!empty($user)) {
            return $user['karma'];
        }

        return self::KARMA_EMPTY;
    }

    public function incrementUserKarma(string $userName)
    {
        $karma = $this->getUserKarma($userName);
        $karma += self::KARMA_ONE_STEP;

        $this->store->add(['name' => $userName, 'karma' => $karma]);
        $this->save();
    }

    private function validateUserName(string $userName)
    {
        return isset($userName) && !empty($userName) && is_string($userName);
    }
}
