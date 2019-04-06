<?php  
namespace \API\Mail;

class Mail implements IMail
{
  // IMailAddress
  private $MailAddressReciever = "";
  public function getMailAddressReciever($MailAddress):IMailAddress
    { return $this->MailAddress; }
  public function setMailAddressReciever(IMailAddress $MailAddress)
    {
      if (!($MailAddress instanceof IMailAddress))
        throw new UnexpectedValueException();

      $this->MailAddressReciever = $MailAddress;
    }

  // IMailAddress
  private $MailAddressSender = "";
  public function getMailAddressSender($MailAddress):IMailAddress
    { return $this->MailAddress; }
  public function setMailAddressSender(IMailAddress $MailAddress)
    {
      if (!($MailAddress instanceof IMailAddress))
        throw new UnexpectedValueException();

      $this->MailAddressSender = $MailAddress;
    }
  // IMessage
  private $Message;
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
    $this->Header = new Header();
    $this->Content = new Content();
  }
}