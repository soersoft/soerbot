<?php

use SoerBot\src\WebServer\WebHookServerResearch;

function __autoload($class_name) {
    require_once $class_name . '.php';
  }
  
return function () {
    return new WebHookServerResearch();
};