<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;

interface IPostSender extends ICreateInstance
{
    function send(IMail $mail):void;
}