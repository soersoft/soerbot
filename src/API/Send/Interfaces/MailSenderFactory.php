<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;

class MailSenderFactory
{
    /**
     * 
     */
    private static $instancesOfIMailSender = array();
    /**
     * 
     */
    private static function scan():array
    {
        return \API\Tools\ClassFinder.findClasses(IMailSender::class);
    }

    /**
     * 
     */
    private static function createIntances(array $classes)
    {
        $res = array();
        foreach($classes as $class)
        {
            if (!($class instanceof IMailSender) || !($class instanceof ICreateInstance))
                continue;
            $res[]=((IMailSender)($class)).CreateInstance();
        }
        return $res;
    }
    /**
     * 
     */
    private static function Subscribe(array $instancesMS, $sendHandler)
    {
        foreach($instancesMS as $instanceMS)
            $instanceMS.eventsHubAddEventHandler("send",$sendHandler);
    }

    /**
     *  NOT COMPLETED NEEDS:
     * - MailPicker.send(IMail)
     */
    public static function refreshMailSenders()
    {
        self::$instancesOfIMailSender = array();// GC Choul kill old ones afterwords
        $classes = self::scan();
        self::$instancesOfIMailSender = self::createIntances($classes);
        self::Subscribe(self::$instancesOfIMailSender, MailPicker.send);
    }
}