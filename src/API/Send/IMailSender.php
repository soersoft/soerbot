<?php

use SoerBot\API\Common;
use SoerBot\API\Tools;

namespace SoerBot\API\Send;

interface IMailSender extends ICreateInstance
{

    function setAddress(IMailAddress $address):void;
    function createMessage():IMail;

    function sendMessage(IMail $mail):void;
    function onSendMessage(Closure $eventHandler):void;
}