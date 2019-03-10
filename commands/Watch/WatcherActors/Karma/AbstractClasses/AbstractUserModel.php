<?php

namespace SoerBot\Commands\Watch\WatcherActors\Karma\AbstractClasses;

abstract class AbstractUserModel
{
    abstract protected function load();

    abstract protected function save();
}
