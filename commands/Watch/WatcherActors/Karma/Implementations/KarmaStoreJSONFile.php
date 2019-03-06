<?php

namespace SoerBot\Commands\Watch\WatcherActors\Karma\Implementations;

use SoerBot\Commands\Watch\WatcherActors\Karma\Interfaces\KarmaStoreInterface;
use SoerBot\Commands\Watch\WatcherActors\Karma\Exceptions\StoreFileNotFoundException;

/**
 * Class KarmeStoreJSONFile.
 *
 * @package SoerBot\Commands\Watch\WatcherActors\Karma\Implementations
 */
class KarmaStoreJSONFile implements KarmaStoreInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $file;

    /**
     * KarmaStoreJSONFile constructor.
     */
    public function __construct()
    {
        $this->data = [];
        $this->file = __DIR__ . '/../store/karma.json';
    }

    /**
     * @return bool|int
     */
    public function save()
    {
        return file_put_contents($this->file, json_encode($this->data));
    }

    /**
     * Load stored data.
     */
    public function load()
    {
        if (file_exists($this->file)) {
            $this->data = json_decode(file_get_contents($this->file), true);
        } else {
            throw new StoreFileNotFoundException('Karma store file not exist');
        }
    }

    /**
     * @return array
     */
    public function get(string $userName): array
    {
        $key = array_search($userName, array_column($this->data, 'name'));

        if ($key !== false) {
            return $this->data[$key];
        }

        return [];
    }

    /**
     * @param array $data
     */
    public function add(array $data)
    {
        if (empty($this->get($data['name']))) {
            array_push($this->data, $data);
        } else {
            $key = array_search($data['name'], array_column($this->data, 'name'));
            $this->data[$key] = $data;
        }
    }
}
