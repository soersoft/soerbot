<?php

use API\Common;
use API\Tools;

namespace API\Send;

class PostSenderStorage
{
    /**
     * instance of InstancesStorage
     */
    private static $_instancesStorage = null;


    private function __clone () {}
    private function __wakeup () {}
    private function __construct () {}

    public static function getInstancesStorage(): InstancesStorage
    {
        if (self::$_instancesStorage == null)
            self::$_instancesStorage = new InstancesStorage();

        return self::$_instancesStorage;
    }

    /**
     * refresh list of IPostSender instances
     */
    public static function refresh()
    {
        $instancesStorage = getInstancesStorage();

        $factory = new API\Send\PostSenderFactory();
        $instancesStorage->refreshInstances($factory);

        $instancesStorage->instances = $factory->test($instancesStorage->instances);
    }
}