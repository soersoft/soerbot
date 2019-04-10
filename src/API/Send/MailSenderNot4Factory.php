<?php

namespace API\Send;

use API\Mail\{Mail, IMail};

/**
 * All classes are inherits from this not will register on factory
 */
class MailSenderNot4Factory extends AMailSender
{
    /**
     * return instance of this class
     * - implements:
     *  - API.Common.ICreateInstance
     * @return instance of this class
     */
    public function CreateInstance(): object
    {
        return new MailSenderNot4Factory();
    }
}