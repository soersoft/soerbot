<?php  
namespace \API\Mail;

class MailAddress implements IMailAddress
{
  // IAddress
  private $Address = "";
  public function getAddress($Address):string
    { return $this->Address;}
  public function setAddress(string $Address)
    {
      if (!($Address instanceof string))
        throw new UnexpectedValueException();

      $this->Address = $Address;
    }

  // IAddressee
  private $Addressee = "";
  public function getAddressee():string
    { return $this->Addressee;}
  public function setAddressee(string $Addressee)
    {
      if (!($Addressee instanceof string))
        throw new UnexpectedValueException();

      $this->Addressee = $Addressee;
    }
}