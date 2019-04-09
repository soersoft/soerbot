<?php

namespace API\Send;

use API\Common;
use API\Tools;

/**
 * All classes are inherits from this not will register on factory
 */
class MailSenderNot4Factory extends AMailSender
{
    /**
     * implements:
     * - API.Send.IMailSender
     * 
     * @return IMail
     */
    public function createMessage():IMail
    {
        return new Mail();
    }


}