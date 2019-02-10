<?php

namespace SoerBot\Commands\Quiz\Interfaces;

interface QuizStoreInterface
{
    public function get();

    public function add(array $args);
}
