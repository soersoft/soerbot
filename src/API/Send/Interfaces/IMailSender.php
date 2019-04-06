<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;

interface IMailSender extends ICreateInstance, IEventsHub
{
    function setAddress(IMailAddress $address):void;
    function createMessage():IMail;
    function sendMessage(IMail $mail):void;
}