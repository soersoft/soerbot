<?php

namespace SoerBot\Classes\Interfaces;

interface StoreInterface
{
    public function save(array $data);

    public function load();
}
