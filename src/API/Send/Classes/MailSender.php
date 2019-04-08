<?php

use \API\Common;
use \API\Tools;

namespace \API\Send;
/**
 * supported interfaces:
 * - API.SendIMailSender
 *  - API.Common.ICreateInstance
 */
abstract class MailSender implements IMailSender
{
    /**
     * 
     * event support
     *  - DRY: for 
     *    - using API.Tools.Event
     * @var sendMessageEvent
     */
    private $_eventSendMessage;

    /**
     * IMailAddress for create message
     * @var IMailAddress;
     */
    protected $mailAddress;

    public function __construct()
    {
      $this->_eventSendMessage = new ApiEvent();
    }
    /**
     * return instance of this class
     * - implements:
     *  - API.Common.ICreateInstance
     * @return instance of this class
     */
    public static function CreateInstance(): object
    {
        return new MailSender();
    }

    /**
     * Subscribe event handler to event sendMessage
     * - implements:
     *  - API.Send.IMailSender
     * 
     *@param $feventHandler function are react to event, handle event
     *  - instance of Closure https://www.php.net/closure
     * 
     * @throws UnexpectedValueException
     */
    function onSendMessage(Closure $eventHandler):void
    {
        if (!($function instanceof Closure))
            throw new UnexpectedValueException();

        $this->_eventSendMessage->eventAddHandler($eventHandler);
    }
    /**
     * implements:
     * - API.Send.IMailSender
     */
    public function sendMessage(IMail $mail):void
    {
        $this->_eventSendMessage->eventLaunch([$mail]);
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


}