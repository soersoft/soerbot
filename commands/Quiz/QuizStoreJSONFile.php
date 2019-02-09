<?php

namespace SoerBot\Commands\Quiz;

use SoerBot\Commands\Quiz\Interfaces\QuizStoreInterface;

class QuizStoreJSONFile implements QuizStoreInterface
{
    private $data;
    private $file;

    public function __construct()
    {
        $this->data = [];
        $this->file = __DIR__ . '/Store/questions.json';
    }

    public function save()
    {
        \file_put_contents($this->file, json_encode($this->data));
    }

    public function load()
    {
        if (file_exists($this->file)) {
            $this->data = json_decode(file_get_contents($this->file), true);
        }
    }

    public function get()
    {
        return $this->data[array_rand($this->data)];
    }

    /**
     * Добавляет
     */
    public function add(array $args)
    {
        array_push($this->data, ['question' => $args[0], 'answer' => $args[1], 'tags' => $args[2]]);

        return true;
    }
}
