<?php

namespace API\Send;

use API\Common;
use API\Tools;

interface IPostSender extends ICreateInstance, ITest
{
    function send(IMail $mail):void;
}