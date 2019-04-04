<?php  
namespace \API\Mail;

class Message implements IMessage
{
  // IAddress
  private $Header = "";
  public function getHeader($Header):string
    { return $this->Header; }
  public function setHeader(string $Header)
    {
      if (!($Header instanceof string))
        throw new UnexpectedValueException();

      $this->Header = $Header;
    }

  // IContent
  private $Content = null;
  public function getContent():JSON
    { return $this->Content; }
  public function setContent(JSON $Content)
    {
      if (!($Content instanceof JSON))
        throw new UnexpectedValueException();

      $this->Content = $Content;
    }
}