<?php  
namespace \API\Mail;

class Mail implements IMail
{
    // IMailAddress
    private $MailAddressReciever = null;
    public function getMailAddressReciever():IMailAddress
       { return $this->MailAddress; }
    public function setMailAddressReciever(IMailAddress $MailAddress)
    {
        if (!($MailAddress instanceof IMailAddress))
            throw new UnexpectedValueException();

        $this->MailAddressReciever = $MailAddress;
    }

    // IMailAddress
    private $MailAddressSender = null;
    public function getMailAddressSender():IMailAddress
       { return $this->MailAddress; }
    public function setMailAddressSender(IMailAddress $MailAddress)
    {
        if (!($MailAddress instanceof IMailAddress))
            throw new UnexpectedValueException();

        $this->MailAddressSender = $MailAddress;
      }
    // IMessage
    private $Message = null;
    public function getMessage():IMessage
      { return $this->Message; }
    public function setMessage(IMessage $Message)
    {
        if (!($Message instanceof IMessage))
           throw new UnexpectedValueException();

        $this->Message = $Message;
    }

    public function __construct()
    {
        $this->MailAddressReciever = new MailAddress();
        $this->MailAddressSender = new MailAddress();
        $this->Message = new Message();
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
          case 'MailAddressReciever':
              return $this->getMailAddressReciever();
          case 'MailAddressSender':
              return $this->getMailAddressSender();
          case 'Message':
              return $this->getMessage();
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
          case 'MailAddressReciever':
              return $this->setMailAddressReciever($value);
          case 'MailAddressSender':
              return $this->setMailAddressSender($value);
          case 'Message':
              return $this->setMessage($value);
          default:
              $class = __CLASS__;
              throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
    }
}