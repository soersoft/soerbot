<?php

namespace SoerBot\Commands\Leaderboard\Interfaces;

interface LeaderBoardStoreInterface
{
    public function save();

    public function load();

    public function get(string $username);

    public function add(array $args);

    public function remove(string $username);

    public function toArray();
}
