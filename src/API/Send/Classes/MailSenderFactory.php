<?php

use \API\Common;
use \API\Tools;
use \API\Mail;

namespace \API\Send;

class MailSenderFactory implements IFactory
{
    /**
     * get all classes, implements IMailSender
     * - API.Common.IFactory
     *  - scan()
     */
    public function scan():array
    {
        return \API\Tools\ClassFinder.findClasses(IMailSender::class);
    }

    /**
     * createIntances of all found casses implements IMailSender
     * - API.Common.IFactory
     *  - createIntances()
     */
    public function createIntances(array $classes):array
    {
        $res = array();
        foreach($classes as $class)
        {
            if (!($class instanceof IMailSender) || !($class instanceof ICreateInstance))
                continue;
            // $res[]=((IMailSender)($class)).CreateInstance();
            $res[]=$class.CreateInstance();
        }
        return $res;
    }
    /**
     * Subscribe MailPicker.send(IMail) to event send(IMail)
     * for each instance
     */
    public function subscribe(array $instances)
    {
        foreach($instances as $instance)
        {
            $my_fun = function ($arg){
                if (!(count($arg)==1))
                    throw new UnexpectedValueException();
                $mail = $arg[0];
                if (!($mail instanceof  \API\Mail\IMail))
                    throw new UnexpectedValueException();
                MailPicker.send($mail);
            };
            $instance.onSendMessage($my_fun);
        }
    }

}