<?php

namespace SoerBot\Services;

use SoerBot\Database\Models\Rank;
use SoerBot\Services\Interfaces\RankServiceInterface;

class RankService implements RankServiceInterface
{
    /**
     * Update user rank.
     *
     * @param $user
     * @param int $rank
     */
    public function update($user, int $rank)
    {
        Rank::firstOrCreate(['user' => $user])->increment('rank', $rank);
    }

    /**
     * Get user rank.
     *
     * @param $user
     * @return int
     */
    public function getRank($user)
    {
        return Rank::where('user', $user)->first()->rank;
    }
}
