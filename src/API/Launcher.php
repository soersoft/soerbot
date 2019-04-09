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
