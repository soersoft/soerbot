<?php

namespace SoerBot\Commands\Leaderboard\Store;

use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;
use SoerBot\Commands\Leaderboard\Exceptions\StoreFileNotFoundException;
use SoerBot\Commands\Leaderboard\Traits\LeaderBoardStoreServiceMethods;
use SoerBot\Commands\Leaderboard\Exceptions\TooFewArgumentsForUserAdding;

class LeaderBoardStoreJSONFile implements LeaderBoardStoreInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var bool|string
     */
    private $file;

    use LeaderBoardStoreServiceMethods;

    public function __construct($filename)
    {
        $this->data = [];
        $this->file = realpath($filename);
    }

    public function save()
    {
        return file_put_contents($this->file, json_encode($this->data));
    }

    public function load()
    {
        if (!file_exists($this->file)) {
            throw new StoreFileNotFoundException('Could not find the json file for loading');
        }

        $this->data = json_decode(file_get_contents($this->file), true);
    }

    public function add(array $args)
    {
        if (count($args) < 2) {
            throw new TooFewArgumentsForUserAdding();
        }

        $username = $args[0];
        $rewards = $args[1];

        $this->remove($username);
        array_push($this->data, ['username' => $username, 'rewards' => $rewards]);
    }

    public function get($username)
    {
        return $this->first($this->data, function ($user) use ($username) {
            return $user['username'] === $username;
        });
    }

    public function remove($username)
    {
        if ($this->userExists($username)) {
            $this->data = $this->where($this->data, function ($user) use ($username) {
                return $user['username'] !== $username;
            });
        }

        return null;
    }

    protected function userExists($username)
    {
        return $this->exists($this->data, 'username', $username);
    }
}
