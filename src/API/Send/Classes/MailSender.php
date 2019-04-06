<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;
/**
 * supported interfaces:
 * - API.SendIMailSender
 *  - API.Common.ICreateInstance
 *  - API.Tools.IEventsHub
 */
abstract class MailSender implements IMailSender
{
    /**
     * 
     * events support
     *  - DRY: for 
     *    - implements API.Tools.IEventsHub 
     *    - using API.Tools.EventsHub
     * @var EventsHub
     */
    private $_eventsHub;
    /**
     * IMailAddress for create message
     * @var IMailAddress;
     */
    protected $mailAddress;

    public function __construct()
    {
      $this->_eventsHub = new EventsHub();
    }

    /**
     * implements:
     * - API.Send.IEventsHub
     */
    function eventsHubAddEventHandler(string $eventName, Closure $eventHandler):void
    {
        $this->_eventsHub->eventsHubAddEventHandler($eventName, $eventHandler);
    }
    /**
     * implements:
     * - API.Send.IEventsHub
     */
    function eventsHubLaunchEvent(string $eventName, array $arg = null):void
    {
        $this->_eventsHub->eventsHubLaunchEvent($eventName, $arg);
    }

    /**
     * implements:
     * - API.Common.ICreateInstance
     * @return instance of this class
     */
    public static function CreateInstance(): object
    {
        return new MailSender();
    }

    /**
     * implements:
     * - API.Send.IMailSender
     */
    public function setAddress(IMailAddress $address):void
    {
        $this->mailAddress = $address;
    }
    /**
     * implements:
     * - API.Send.IMailSender
     * 
     * @return IMail
     */
    public abstract function createMessage():IMail;

    /**
     * implements:
     * - API.Send.IMailSender
     */
    public function sendMessage(IMail $mail):void
    {
        $this->eventsHubLaunchEvent("send",["IMail"=>$mail]);
    }
}