<?php

namespace API\Send;

use API\Common\{ICreateInstance, ITest};

interface IPostSender extends ICreateInstance, ITest
{
    function send(IMail $mail):void;
}