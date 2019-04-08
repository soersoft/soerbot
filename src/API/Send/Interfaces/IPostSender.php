<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;

interface IPostSender extends ICreateInstance, ITest
{
    function send(IMail $mail):void;
}