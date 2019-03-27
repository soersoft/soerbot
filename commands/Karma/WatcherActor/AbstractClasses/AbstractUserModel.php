<?php

namespace SoerBot\Commands\Karma\WatcherActor\AbstractClasses;

abstract class AbstractUserModel
{
    abstract protected function load();

    abstract protected function save();
}
