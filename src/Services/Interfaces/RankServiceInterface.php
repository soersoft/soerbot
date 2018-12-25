<?php

namespace SoerBot\Services\Interfaces;

interface RankServiceInterface
{
    /**
     * Update user rank.
     *
     * @param $user
     * @param int $rank
     */
    public function update($user, int $rank);

    /**
     * Get user rank.
     *
     * @param $user
     * @return int
     */
    public function getRank($user);
}
