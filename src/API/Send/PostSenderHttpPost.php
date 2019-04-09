<?php

use SoerBot\API\Common;
use SoerBot\API\Tools;

namespace SoerBot\API\Send;
/***
 * It's provide possibility to send IMail as 
 * - post reguest
 * - interface IPostSender extends 
 *  - ICreateInstance,
 *  - ITest
 */
class PostSenderHttpPost implements IPostSender
{
    /**
     * returns instance of this class
     * - implements:
     *  - API.Common.ICreateInstance
     * @return instance of this class
     */
    public static function CreateInstance(): object
    {
        return new PostSenderHttpPost();
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
     * - post reguest
     *  - probably needs more deep pasrce adderess
     * - implements:
     *  - API.Send.IPostSender
     * - see:
     *  - http://thisinterestsme.com/sending-json-via-post-php/
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
        
        $url = "{$addressReciever}\\{$addresseeReciever}\\{$addressSender}\\{$addresseeSender}\\{$messageHeader}";
        
        //Initiate cURL.
        $ch = curl_init($url);
        
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messageContent);
        
        //Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
        
        //Execute the request
        $result = curl_exec($ch);
    }

}