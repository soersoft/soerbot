<?php  
  namespace \API\Common;

class Mail implements IMail
{
  // IMailAddress
  private $MailAddress = "";
  public function getMailAddress($MailAddress):IMailAddress
    { return $this->MailAddress; }
  public function setMailAddress(IMailAddress $MailAddress)
    {
      if (!($MailAddress instanceof IMailAddress))
        throw new UnexpectedValueException();

      $this->MailAddress = $MailAddress;
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