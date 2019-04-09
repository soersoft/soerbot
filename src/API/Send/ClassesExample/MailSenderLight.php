<?php

use API\Common;
use API\Tools;

namespace API\Send;
/**
 * supported interfaces:
 * - API.SendIMailSender
 *  - API.Common.ICreateInstance
 */
class MailSenderLight extends AMailSender
{
    /**
     * implements:
     * - API.Send.IMailSender
     * 
     * @return IMail
     */
    public function createMessage():IMail
    {
        $mail = new Mail();
    }
}