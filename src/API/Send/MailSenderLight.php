<?php

use SoerBot\API\Common;
use SoerBot\API\Tools;

namespace SoerBot\API\Send;
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