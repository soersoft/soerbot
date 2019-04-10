<?php

namespace API\Send;

use API\Tools\{ApiEvent};
use API\Mail\{IMail, IMailAddress, Message};

/**
 * supported interfaces:
 * - API.SendIMailSender
 *  - API.Common.ICreateInstance
 */
abstract class AMailSender implements IMailSender
{

    public function __construct()
    {
      $this->_eventSendMessage = new ApiEvent();
    }

    /**
     * 
     * event support
     *  - DRY: for 
     *    - using API.Tools.Event
     * @var sendMessageEvent
     */
    private $_eventSendMessage;

    /**
     * MailSample will used as address sender and receiver sourse for new message
     * @val    address sender and receiver sourse
     *  - IMail
     */
    protected $mailSample = null;
    /**
     * MailSample will used as address sender and receiver sourse for new message
     * @return $mailSample address sender and receiver sourse
     *  - IMail
     *  - API.Send.IMailSender
     */
    function getMailSample():IMail
    { return $this->mailSample;}
    /**
     * MailSample will used as address sender and receiver sourse for new message
     * @param $mailSample address sender and receiver sourse
     *  - IMail
     *  - API.Send.IMailSender
     */
    function setMailSample(IMail $mailSample):void
    {
        if (!($mailSample instanceof IMail))
            throw new UnexpectedValueException();
        $this->mailSample = $mailSample;
    }

    /**
     * Getters implements, thanks to:
     * - https://ttmm.io/tech/php-read-attributes/
     * 
     * Magic getter for our object.
     *
     * @param string $field
     * @throws UnexpectedValueException Throws an exception if the field is invalid.
     * @return mixed
     */
    public function __get(string $field ) 
    {
        switch( $field ) 
        {
          case 'mailSample':
              return $this->getMailSample();
          default:
              $class = __CLASS__;
              throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
    }

    /**
     * Setters implements, thanks to:
     * - https://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     * - https://ttmm.io/tech/php-read-attributes/
     * 
     * Magic setter for our object.
     *
     * @param string $field
     * @param mixed $value
     * @throws UnexpectedValueException Throws an exception if the field is invalid.
     * @return void
     */
    public function __set(string $field, mixed $value )
    {
        switch( $field ) 
        {
          case 'mailSample':
              return $this->setMailSample($value);
          default:
              $class = __CLASS__;
              throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
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
     * Create message using  $mailSample as address sender and receiver sourse
     * @param $messageContext
     *  - JSON string of content 
     *  - API.Send.IMailSender
     */
    function createMessage(string $messageContent):IMail
    {
        $class = __CLASS__;
        // check $messageContent for JSON
        if (!($this->mailSample instanceof IMail))
            throw new UnexpectedValueException("{$class}->mailSample has been not defined");
        $res = clone($this->mailSample);
        $message = new Message();
        $message->Header = $this->mailSample->getMessage()->getHeader();
        $message->Content = $messageContent;
        $res->setMessage($message);
        
        return $res;
    }

    /**
     * return instance of this class
     * - implements:
     *  - API.Common.ICreateInstance
     * @return instance of this class
     */
    public function CreateInstance(): object
    {
        return new self();
    }

}