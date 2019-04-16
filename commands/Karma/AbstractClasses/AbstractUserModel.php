<?php

namespace SoerBot\Commands\Karma\AbstractClasses;

abstract class AbstractUserModel
{
    abstract protected function load();

    abstract protected function save();
}
