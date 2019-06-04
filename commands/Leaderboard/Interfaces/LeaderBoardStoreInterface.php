<?php

namespace SoerBot\Commands\Leaderboard\Interfaces;

interface LeaderBoardStoreInterface
{
    public function save(array $data);

    public function load();
}
