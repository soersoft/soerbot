<?php

namespace SoerBot\Database\Repositories;

use SoerBot\Database\Settings\Capsule;
use Illuminate\Database\Query\Expression;

class UserListRepository
{
    /**
     * @var Capsule
     */
    private $db;

    /**
     * UserListRepository constructor.
     */
    public function __construct()
    {
        $this->db = new Capsule();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUserList()
    {
        // SELECT users.id as id, users.name as name, award_user.user_from as user_from, awards.type as type
        // FROM users
        // LEFT join award_user ON award_user.user_to = users.id
        // Left join awards ON award_user.type_id = awards.id

        $select = new Expression('
         users.id as id, 
         users.name as name, 
         users.rank as user_rank, 
         award_user.user_from as user_from, 
         awards.type as award_type');

        // TODO рефакторинг.
        return $this->db->table('users')
            ->select($select)
            ->leftJoin('award_user', 'award_user.user_to', '=', 'users.id')
            ->leftJoin('awards', 'award_user.type_id', '=', 'awards.id')
            ->get()
            ->map(function ($item) {
                $awards = $item->award_type ? [['type' => $item->award_type, 'from' => $item->user_from]] : null;

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'rank' => $item->user_rank,
                    'awards' => $awards,
                ];
            })
            ->groupBy('id')
            ->map(function ($item) {
                $first = $item->shift();
                $awards = $item->pluck('awards')->collapse();

                if ($awards->isNotEmpty())
                {
                    $first['awards'] = array_merge($first['awards'], $awards->toArray());
                }

                return $first;
            });
    }
}