<?php

namespace SoerBot\Commands\Voting\Services;

use SoerBot\Commands\Voting\Interfaces\VotingStoreInterface;

/**
 * Class VotingStoreJSONFile.
 *
 * @package SoerBot\Commands\Voting\Services
 */
class VotingStoreJSONFile implements VotingStoreInterface
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
     * VotingStoreJSONFile constructor.
     */
    public function __construct()
    {
        $this->data = [];
        $this->file = __DIR__ . '/../Store/voting.json';
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
        }else {
            $this->createStoreFile();
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->data[array($this->data)];
    }

    /**
     * @param array $args
     * @return bool
     */
    public function add(array $args)
    {
        array_push($this->data, ['voting' => $args[0]]);

        return true;
    }

public function createStoreFile()
{
    try {
        $storeFile = fopen($this->file, 'w');
        fwrite($storeFile, '[]');
        fclose($storeFile);
    } catch (StoreFileNotFoundException $error) {
        throw new StoreFileNotFoundException('Voting store file not exist. The file must be created manually');
    }
}
}