<?php

namespace API\Send;

use API\Tools\{InstancesStorage};

class MailSenderStorage
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
     * refresh list of IMailSender instances
     */
    public static function refresh()
    {
        $instancesStorage = getInstancesStorage();

        $factory = new MailSenderFactory();
        $instancesStorage->refreshInstances($factory);

        $factory->Subscribe($instancesStorage->instances);
    }
}
