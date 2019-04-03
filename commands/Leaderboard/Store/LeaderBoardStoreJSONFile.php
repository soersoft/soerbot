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
    private $data = [];

    /**
     * @var string
     */
    private $file;

    use ArrayServiceMethods;

    public function __construct($filename = __DIR__ . '/../Store/leaderboard.json')
    {
        $this->file = $filename;
    }

    /**
     * Saves all the data to JSON with pretty print.
     * @return bool|int
     */
    public function save()
    {
        return file_put_contents($this->file, json_encode(array_values($this->data), JSON_UNESCAPED_UNICODE |
          JSON_PRETTY_PRINT));
    }

    /**
     * Loads data from JSON file.
     * @throws StoreFileNotFoundException
     * @return bool
     */
    public function load()
    {
        if (!file_exists($this->file)) {
            throw new StoreFileNotFoundException('File ' . $this->file . ' have not found');
        }

        if (empty($content = file_get_contents($this->file))) {
            return true;
        }

        $this->data = json_decode($content, true);

        return $this->data === null ? false : true;
    }

    /**
     * @param array $args
     * @throws TooFewArgumentsForUserAdding
     * @return bool
     */
    public function add(array $args)
    {
        if (count($args) < 2) {
            throw new TooFewArgumentsForUserAdding();
        }
        [$username, $rewards] = $args;
        $this->remove($username);
        array_push($this->data, ['username' => $username, 'rewards' => $rewards]);
    }

    /**
     * @param $username
     * @return mixed|null
     */
    public function get($username)
    {
        return $this->first($this->data, function ($user) use ($username) {
            return strtolower($user['username']) === strtolower($username);
        });
    }

    /**
     * @param $username
     * @return void
     */
    public function remove($username)
    {
        if ($this->userExists($username)) {
            $this->data = $this->where($this->data, function ($user) use ($username) {
                return strtolower($user['username']) !== strtolower($username);
            });
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param $username
     * @return bool
     */
    protected function userExists($username)
    {
        return $this->exists($this->data, 'username', $username);
    }
}
