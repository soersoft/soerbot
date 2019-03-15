<?php

namespace SoerBot\Commands\Karma\WatcherActor\Interfaces;

interface KarmaStoreInterface
{
    public function load();

    public function save();

    public function get(string $userName);

    public function add(array $data);
}
