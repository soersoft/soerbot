<?php

namespace API\Send;

use API\Common\{ICreateInstance, IFactory};
use API\Mail\{IMail};
use API\Tools\{ClassFinder};

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
        $classesFullNames = ClassFinder::findClasses(IMailSender::class);

        //delete all classes inherits from MailSenderNot4Factory
        $classesNot4Factory = array();
        foreach($classesFullNames as $className)
        {
            $reflectiontestClass = new \ReflectionClass($className);
            // if ($className instanceof MailSenderNot4Factory)
            if ($reflectiontestClass->isInstance(MailSenderNot4Factory))
                $classesNot4Factory[] = $className;
        }
        $classesFullNames = \array_diff($classesFullNames, $classesNot4Factory);

        return $classesFullNames;
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
                if (!($mail instanceof  IMail))
                    throw new UnexpectedValueException();
                MailPicker.send($mail);
            };
            $instance.onSendMessage($my_fun);
        }
    }

}