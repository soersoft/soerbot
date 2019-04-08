<?php

use \API\Common;
use \API\Tools;
use \API\Mail;

namespace \API\Send;

class PostSenderFactory
{
    /**
     * get all classes, implements
     * - IPostSender
     */
    public function scan():array
    {
        return \API\Tools\ClassFinder.findClasses(IPostSender::class);
    }

    /**
     * createIntances of all found casses implements IPostSender
     */
    public function createIntances(array $classes):array
    {
        $res = array();
        foreach($classes as $class)
        {
            if (!($class instanceof IPostSender) || !($class instanceof ICreateInstance))
                continue;
            $res[]=$class.CreateInstance();
        }
        return $res;
    }
    /**
     * Subscribe MailPicker.send(IMail) to event send(IMail)
     * for each instance
     * @return List of working instances
     */
    public function test(array $instances):array
    {
        $res = array();
        foreach($instances as $instance)
        {
            if (!($instance instanceof \API\Common\ITest))
                continue;
            if (!($instance->test()))
                continue;

            $res[] = $instance;
        }
        return $res;
    }

}