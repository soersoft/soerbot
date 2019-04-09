<?php

namespace API\Send;

use API\Common;
use API\Tools;

interface IMailSender extends ICreateInstance
{

    function setAddress(IMailAddress $address):void;
    function createMessage():IMail;

    function sendMessage(IMail $mail):void;
    function onSendMessage(Closure $eventHandler):void;
}