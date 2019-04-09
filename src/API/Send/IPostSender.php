<?php

use SoerBot\API\Common;
use SoerBot\API\Tools;

namespace SoerBot\API\Send;

interface IPostSender extends ICreateInstance, ITest
{
    function send(IMail $mail):void;
}