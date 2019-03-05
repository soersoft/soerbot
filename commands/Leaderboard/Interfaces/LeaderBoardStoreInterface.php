<?php

namespace SoerBot\Commands\Leaderboard\Interfaces;

interface LeaderBoardStoreInterface
{
    public function save();

    public function load();

    public function get($username);

    public function add(array $args);

    public function remove($username);
}
