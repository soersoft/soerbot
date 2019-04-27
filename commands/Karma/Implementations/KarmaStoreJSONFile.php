<?php

namespace SoerBot\Commands\Karma\Implementations;

use SoerBot\Commands\Karma\Interfaces\KarmaStoreInterface;
use SoerBot\Commands\Karma\Exceptions\StoreFileNotFoundException;

/**
 * Class KarmeStoreJSONFile.
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
        $this->file = __DIR__.'/../store/karma.json';
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
            $this->createStoreFile();
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

    /**
     * Create store file.
     */
    public function createStoreFile()
    {
        try {
            $storeFile = @fopen($this->file, 'w');

            if (!$storeFile) {
                throw new StoreFileNotFoundException('Failed to create karma store file. Check the path to file');
            }
            if (!fwrite($storeFile, '[]')) {
                throw new StoreFileNotFoundException('Failed to write in karma store file. Check the path to file');
            }
            if (!fclose($storeFile)) {
                throw new StoreFileNotFoundException('Failed to save karma store file. Check the path to file');
            }
        } catch (StoreFileNotFoundException $error) {
            throw new StoreFileNotFoundException($error->getMessage());
        }
    }
}
