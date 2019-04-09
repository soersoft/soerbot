<?php  
namespace API\Tools;

interface IEvent
{
    function eventAddHandler(Closure $function):void;
    function eventLaunch (array $arg = null):void;
}