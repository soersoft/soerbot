<?php

namespace SoerBot\Commands\Leaderboard\AdvImplementations;

interface LeaderBoardStoreInterface
{
    public function save(array $data);

    public function load();
}
