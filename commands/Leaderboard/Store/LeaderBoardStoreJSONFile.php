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

    /**
     * LeaderBoardStoreJSONFile constructor.
     *
     * @param string $filename
     * @throws StoreFileNotFoundException
     */
    public function __construct(string $filename = __DIR__ . '/../Store/leaderboard.json')
    {
        $this->file = $this->init($filename);
    }

    /**
     * Loads data from JSON file.
     *
     * @throws StoreFileNotFoundException
     * @return bool
     */
    public function load()
    {
        if (!file_exists($this->file)) {
            throw new StoreFileNotFoundException('File ' . $this->file . ' was not found.');
        }

        if (empty($content = file_get_contents($this->file))) {
            return true;
        }

        $this->data = json_decode($content, true);

        return $this->data === null ? false : true;
    }

    /**
     * Saves all the data to JSON with pretty print.
     *
     * @return bool|int
     */
    public function save()
    {
        return @file_put_contents($this->file, json_encode(array_values($this->data), JSON_UNESCAPED_UNICODE |
            JSON_PRETTY_PRINT));
    }

    /**
     * @param string $username
     * @return mixed|null
     */
    public function get(string $username)
    {
        return $this->first($this->data, function ($user) use ($username) {
            return strtolower($user['username']) === strtolower($username);
        });
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
     * @param string $username
     * @return void
     */
    public function remove(string $username)
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

    /*
     * Init LeaderBoardStorage.
     *
     * @param string $filename
     * @return string
     * @throws StoreFileNotFoundException
     */
    protected function init(string $filename): string
    {
        if (!file_exists($filename)) {
            throw new StoreFileNotFoundException('File ' . $filename . ' was not found.');
        }

        return $filename;
    }

    /**
     * @param string $username
     * @return bool
     */
    protected function userExists(string $username)
    {
        return $this->exists($this->data, 'username', $username);
    }
}
