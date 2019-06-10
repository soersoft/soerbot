<?php

namespace SoerBot\Classes;

use SoerBot\Classes\Traits\HasRewards;

class User
{
    use HasRewards;

    /**
     * @var string
     */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns name of the user.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
