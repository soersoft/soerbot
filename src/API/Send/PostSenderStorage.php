<?php



namespace API\Send;
use API\Tools\{InstancesStorage};

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
        $instancesStorage = self::getInstancesStorage();

        $factory = new PostSenderFactory();
        $instancesStorage->refreshInstances($factory);

        $instancesStorage->instances = $factory->test($instancesStorage->instances);
    }
}