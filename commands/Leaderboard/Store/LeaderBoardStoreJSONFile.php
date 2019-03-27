<?php

namespace SoerBot\Commands\Leaderboard\Store;

use SoerBot\Commands\Leaderboard\Traits\ArrayServiceMethods;
use SoerBot\Commands\Leaderboard\Interfaces\LeaderBoardStoreInterface;
use SoerBot\Commands\Leaderboard\Exceptions\StoreFileNotFoundException;
use SoerBot\Commands\Leaderboard\Exceptions\TooFewArgumentsForUserAdding;

class LeaderBoardStoreJSONFile implements LeaderBoardStoreInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $file;

    use ArrayServiceMethods;

    public function __construct($filename)
    {
        $this->data = [];
        $this->file = $filename;
    }

    public function save()
    {
        return file_put_contents($this->file, json_encode(array_values($this->data), JSON_UNESCAPED_UNICODE |
          JSON_PRETTY_PRINT));
    }

    public function load()
    {
        if (!file_exists($this->file)) {
            throw new StoreFileNotFoundException('File ' . $this->file . ' have not found');
        }

        return (($this->data = json_decode(file_get_contents($this->file), true)) == true) ?? false;
    }

    public function add(array $args)
    {
        if (count($args) < 2) {
            throw new TooFewArgumentsForUserAdding();
        }

        list($username, $rewards) = $args;

        $this->remove($username);
        array_push($this->data, ['username' => $username, 'rewards' => $rewards]);
    }

    public function get($username)
    {
        return $this->first($this->data, function ($user) use ($username) {
            return strtolower($user['username']) === strtolower($username);
        });
    }

    public function remove($username)
    {
        if ($this->userExists($username)) {
            $this->data = $this->where($this->data, function ($user) use ($username) {
                return strtolower($user['username']) !== strtolower($username);
            });
        }

        return null;
    }

    public function toArray()
    {
        return $this->data;
    }

    protected function userExists($username)
    {
        return $this->exists($this->data, 'username', $username);
    }
}
