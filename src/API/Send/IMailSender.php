<?php

namespace API\Send;

use API\Common\{ICreateInstance};
use API\Mail\{IMail, IMailAddress, IContent};

interface IMailSender extends ICreateInstance
{
    /**
     * MailSample will used as address sender and receiver sourse for new message
     * @return $mailSample address sender and receiver sourse
     *  - IMail
     */
    function getMailSample():IMail;
    /**
     * MailSample will used as address sender and receiver sourse for new message
     * @param $mailSample address sender and receiver sourse
     *  - IMail
     */
    function setMailSample(IMail $mailSample):void;
    /**
     * Create message using  $mailSample as address sender and receiver sourse
     * @param $messageContext
     *  - JSON string of content 
     */
    function createMessage(string $messageContent):IMail;

    function sendMessage(IMail $mail):void;
    function onSendMessage(\Closure $eventHandler):void;
}