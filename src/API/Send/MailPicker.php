<?php

namespace API\Send;

use API\Common;
use API\Tools;

class MailPicker
{
    private function __clone () {}
    private function __wakeup () {}
    private function __construct () {}

    /**
     * send mail using available instances of IPostSender
     * - for now
     *  - HttpGet
     *  - HttpPost
     * - Maybe needs make some kind of sender selector, 
     *  - based for example on IMail.MailAddress
     *  - for this moment try to send by all PostSenders
     * 
     * @param $mail message to send
     *  - instance of IMail
     */
    public static function send(IMail $mail)
    {
        $postSenders = PostSenderStorage::getInstancesStorage()->instances;
        foreach ($postSenders as $postSender)
        {
            if (!($postSender instanceof IPostSender))
                continue;
            $postSender->send($mail);
        }
    }
}
