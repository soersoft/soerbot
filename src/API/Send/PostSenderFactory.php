<?php

namespace API\Send;

use API\Common\{IFactory, ITest};
use API\Tools\{ClassFinder};


class PostSenderFactory implements IFactory
{
    /**
     * get all classes, implements IPostSender
     * - API.Common.IFactory
     *  - scan()
     */
    public function scan():array
    {
        return ClassFinder::findClasses(IPostSender::class);
    }

    /**
     * createIntances of all found casses implements IPostSender
     * - API.Common.IFactory
     *  - createIntances()
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
            if (!($instance instanceof ITest))
                continue;
            if (!($instance->test()))
                continue;

            $res[] = $instance;
        }
        return $res;
    }

}