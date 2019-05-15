<?php

namespace SoerBot\Commands\Karma\Implementations;

use SoerBot\Commands\Karma\AbstractClasses\AbstractUserModel;
use SoerBot\Commands\Karma\Exceptions\InvalidUserNameException;
use SoerBot\Commands\Karma\Exceptions\StoreFileNotFoundException;

class UserModel extends AbstractUserModel
{
    /**
     * @var \SoerBot\Commands\Karma\Implementations\KarmaStoreJSONFile
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

    public function getKarma(string $userName): int
    {
        if (!$this->validateName($userName)) {
            throw new InvalidUserNameException('Invalid username. Username must be a string');
        }

        $user = $this->store->get($userName);

        if (!empty($user)) {
            return $user['karma'];
        }

        return self::KARMA_EMPTY;
    }

    public function incrementKarma(string $userName)
    {
        $karma = $this->getKarma($userName);
        $karma += self::KARMA_ONE_STEP;

        $this->store->add(['name' => $userName, 'karma' => $karma]);
        $this->save();
    }

    private function validateName(string $userName)
    {
        return isset($userName) && !empty($userName) && is_string($userName);
    }
}
