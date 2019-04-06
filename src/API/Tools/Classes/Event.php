<?php  
namespace \API\Tools;

class Event implements IEvent
{
    // IEvent
    /**
     *  event handlers list
     */
    private $_eventHandlers = array();
    /**
     * subscribe to event: 
     * - add(subscribe) event handler
     *
     * @param $function function are react to event, handle event
     *  - instance of Closure https://www.php.net/closure
     * 
     * @throws UnexpectedValueException
     */
    public function eventAddHandler(Closure $function):void
    {
        if (!\is_callable($function) && !($function instanceof Closure))
          throw new UnexpectedValueException();

        // add value to array
        // https://stackoverflow.com/questions/676677/how-to-add-elements-to-an-empty-array-in-php
        $this->_eventHandlers[] = $function; // this way 
        // array_push($this->_eventHandlers, $function); // or this (like to stack)
    }

    /**
     * launch event
     * - call all eventHandlers for this event
     * 
     * @param $arg - list parameters to function
     *  - untyped array
     *    - danger can be exceptions, due use uncontrolled values
     */
    public function eventLaunch (array $arg = null):void
    {
        foreach ($this->_eventHandlers as $eventHandler) 
          call_user_func($eventHandler, $arg);
    }

}