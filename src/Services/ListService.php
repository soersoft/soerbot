<?php

namespace SoerBot\Services;

use SoerBot\Database\Repositories\UserListRepository;

class ListService
{
    /**
     * @var UserListRepository
     */
    private $repository;

    /**
     * ListService constructor.
     */
    public function __construct()
    {
        $this->repository = new UserListRepository();
    }

    /**
     * User list with rank and awards.
     *
     * @return \Illuminate\Support\Collection
     */
    public function userList()
    {
        return $this->repository->getUserList();
    }
}
