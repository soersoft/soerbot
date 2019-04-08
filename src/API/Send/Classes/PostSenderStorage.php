<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;
/**
 * supported interfaces:
 * - API.SendIMailSender
 *  - API.Common.ICreateInstance
 */
class PostSenderStorage
{
    /**
     * 
     */
    private static $instancesOfIPostSender = array();
    /**
     * clear list of IPostSender instances
     */
    public static function clearPostSenders()
    {
        self::$instancesOfIPostSender = array();
        gc_collect_cycles(); // GC Should kill old ones
    }
    /**
     * refresh list of IMailSender instances
     * - NOT COMPLETED NEEDS:
     *  - MailPicker.send(IMail)
     */
    public static function refreshPostSenders()
    {
        $factory = new \API\Send\MailSenderFactory();
        
        $classes = $factory->scan();
        self::$instancesOfIMailSender = $factory->createIntances($classes);
        $factory->Subscribe(self::$instancesOfIMailSender);
    }
}