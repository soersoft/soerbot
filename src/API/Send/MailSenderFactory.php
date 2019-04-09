<?php

namespace API\Send;

use API\Common;
use API\Tools;
use API\Mail;

class MailSenderFactory implements IFactory
{
    /**
     * get all classes, implements IMailSender
     * - except all classes inherits from MailSenderNot4Factory
     *  - https://stackoverflow.com/questions/369602/php-delete-an-element-from-an-array?rq=1
     * - API.Common.IFactory
     *  - scan()
     */
    public function scan():array
    {
        $classes = API\Tools\ClassFinder.findClasses(IMailSender::class);

        //delete all classes inherits from MailSenderNot4Factory
        $classesNot4Factory = array();
        foreach($classes as $class)
            if ($class instanceof MailSenderNot4Factory)
                $classesNot4Factory[] = $class;
        \array_diff($classes, $classesNot4Factory);

        // WTF? \unset($classes[i]);
        // $i = 0;
        // while ($i < count($classes))
        // {
        //     if ($classes[i] instanceof MailSenderNot4Factory)
        //     {
        //         \unset($classes[1]);
        //         continue;
        //     }
        //     i++;
        // }

        return $classes;
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
                if (!($mail instanceof  API\Mail\IMail))
                    throw new UnexpectedValueException();
                MailPicker.send($mail);
            };
            $instance.onSendMessage($my_fun);
        }
    }

}