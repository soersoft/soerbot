<?php

use SoerBot\API\Common;
use SoerBot\API\Tools;

namespace SoerBot\API\Send;
/***
 * It's provide possibility to send IMail as 
 * - get reguest
 * - interface IPostSender extends 
 *  - ICreateInstance,
 *  - ITest
 */
class PostSenderHttpGet implements IPostSender
{
    /**
     * returns instance of this class
     * - implements:
     *  - API.Common.ICreateInstance
     * @return instance of this class
     */
    public static function CreateInstance(): object
    {
        return new PostSenderHttpGet();
    }
    /**
     * Test instance
     * - test service possibility to work
     *  - for now dummy, not enough knowledge
     * - implements:
     *  - API.Common.ITest
     * 
     * @return usefull or usless service
     * - bool: true or false
     */
    public function test(): bool
    {
        return true; // dummy
    }

    /**
     * to sends IMail as 
     * - get reguest
     *  - probably needs more deep pasrce adderess
     * - implements:
     *  - API.Send.IPostSender
     * - see:
     *  - http://docs.php.net/manual/da/function.http-get.php
     * 
     * @param $mail this is needs to send
     *  - instance IMail
     * 
     * @throws UnexpectedValueException
     */
    function send(IMail $mail):void
    {
        if (!($mail instanceof IMail))
            throw new UnexpectedValueException();

        $addressReciever = $mail->getMailAddressReciever()->getAddress();
        $addresseeReciever = $mail->getMailAddressReciever()->getAddressee();
        $addressSender = $mail->getMailAddressSender()->getAddress();
        $addresseeSender = $mail->getMailAddressSender()->getAddressee();
        $messageHeader = $mail->getMessage()->getHeader();
        $messageContent = $mail->getMessage()->getContent();
        
        $url = "{$addressReciever}\\{$addresseeReciever}\\{$addressSender}\\{$addresseeSender}\\{$messageHeader}\\{$messageContent}";
        $res = http_get($url);
    }

}