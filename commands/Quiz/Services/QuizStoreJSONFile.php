<?php

namespace SoerBot\Commands\Quiz\Services;

use SoerBot\Commands\Quiz\Interfaces\QuizStoreInterface;

/**
 * Class QuizStoreJSONFile.
 *
 * @package SoerBot\Commands\Quiz\Services
 */
class QuizStoreJSONFile implements QuizStoreInterface
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
     * QuizStoreJSONFile constructor.
     */
    public function __construct()
    {
        $this->data = [];
        $this->file = __DIR__ . '/../Store/questions.json';
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
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->data[array_rand($this->data)];
    }

    /**
     * @param array $args
     * @return bool
     */
    public function add(array $args)
    {
        array_push($this->data, ['question' => $args[0], 'answer' => $args[1], 'tags' => $args[2]]);

        return true;
    }
}
