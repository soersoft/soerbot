<?php  
namespace \API\Tools;

class EventsHub implements IEventsHub
{
    // IEventsHub
    /**
     *  events Collection<string eventName,Closure eventHandler>
     */
    private $_eventsCollection = array();

    /**
     * subscribe to event: 
     * - add(subscribe) pair eventName - eventHandler
     * 
     * @param $eventName identify event
     *  - string
     * @param $eventHandler function are react to event, handle event
     *  - instance of Closure https://www.php.net/closure
     * @throws UnexpectedValueException
     */
    function eventsHubAddEventHandler(string $eventName, Closure $eventHandler):void
    {
        if (!\is_callable($eventHandler) && !($eventHandler instanceof Closure))
        throw new UnexpectedValueException();

        $this->_eventsCollection[]=[$eventName=>$eventHandler];
    }
    /**
     * launch event by name
     * - call all eventHandlers for this event
     * @param $eventName event to launch
     *  - string
     * @param $arg - list parameters to eventHandler
     *  - untyped array
     *    - danger: can be exceptions, due use uncontrolled values
     */
    function eventsHubLaunchEvent(string $eventName, array $arg = null):void
    {
        // thanks to https://klisl.com/events_php.html
        foreach ($this->_eventsCollection as $eventNameHandler)
            if (array_key_exists($eventName, $eventNameHandler))
                call_user_func($eventNameHandler[$eventName], $arg);
        
    }
}