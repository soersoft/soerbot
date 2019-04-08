<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;
/***
 * It's provide possibility to send IMail as 
 * - get reguest
 */
class PostSenderHttpGet implements IPostSender
// interface IPostSender extends ICreateInstance
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
     * to sends IMail as 
     * - get reguest
     *  - probably needs more deep pasrce adderess
     * - implements:
     *  - API.Send.IPostSender
     * - see:
     *  - http://docs.php.net/manual/da/function.http-get.php
     * 
     *@param $mail this is needs to send
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