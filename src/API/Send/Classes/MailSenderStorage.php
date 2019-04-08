<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;

class MailSenderStorage
{
    /**
     * 
     */
    private static $instancesOfIMailSender = array();
    /**
     * clear list of IMailSender instances
     */
    public static function clear()
    {
        self::$instancesOfIMailSender = array();
        gc_collect_cycles(); // GC Should kill old ones
    }
    /**
     * refresh list of IMailSender instances
     * - NOT COMPLETED NEEDS:
     *  - MailPicker.send(IMail)
     */
    public static function refresh()
    {
        $factory = new \API\Send\MailSenderFactory();
        
        $classes = $factory->scan();
        self::$instancesOfIMailSender = $factory->createIntances($classes);
        $factory->Subscribe(self::$instancesOfIMailSender);
    }
}