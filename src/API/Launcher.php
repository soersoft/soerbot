<?php

use \API\Send;
use \API\Receive;
// use \API\Common;
// use \API\Tools;

namespace \API;

class Launcher
{
    /**
     * instance of app loop
     * @var React\EventLoop\LoopInterface
     */
    private static $loop = null;
    /**
     * instance of app Livia client
     * @var CharlotteDunois\Livia\Client
     */
    private static $client = null;

    private function __clone () {}
    private function __wakeup () {}
    private function __construct () {}

    /**
     * Getters implements, thanks to:
     * - https://ttmm.io/tech/php-read-attributes/
     * 
     * Magic getter for our object.
     *
     * @param string $field
     * @throws UnexpectedValueException Throws an exception if the field is invalid.
     * @return mixed
     */
    public function __get(string $field ) 
    {
        switch( $field ) 
        {
            case 'loop':
                return self::$loop;
            case 'client':
                return self::$client;
            default:
                $class = __CLASS__;
                throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
    }

    /**
     * starts API.Core
     * - needs:
     *  - $aLoop - application's work loop
     *  - $aClient - Livia dicord bot client
     * @param $aLoop - application's work loop, not started yet
     *  - instance of React\EventLoop\LoopInterface
     * 
     * @param $aClient - dicord bot client of Livia framework
     *  - instance of CharlotteDunois\Livia\Client
     * */
    public static function start($aLoop, $aClient): void
    {
        self::$loop = $aLoop;
        self::$client = $aClient;

        MailSenderStorage::refresh();
        PostSenderStorage::refresh();
    }

    /**
     * refresh list of IMailSender instances
     */
    public static function refresh()
    {
        $instancesStorage = getInstancesStorage();

        $factory = new \API\Send\MailSenderFactory();
        $instancesStorage->refreshInstances($factory);

        $factory->Subscribe($instancesStorage->instances);
    }
}
