<?php  
namespace API\Tools;

interface IEventsHub
{
    function eventsHubAddEventHandler(string $eventName, Closure $handler):void;
    function eventsHubLaunchEvent (string $eventName, array $arg = null):void;
}