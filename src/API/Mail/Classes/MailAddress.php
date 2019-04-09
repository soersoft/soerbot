<?php  
namespace \API\Mail;

class MailAddress implements IMailAddress
{
  // IAddress
  private $Address = "";
  public function getAddress():string
    { return $this->Address;}
  public function setAddress(string $Address)
  {
      if (!is_string($Address))
          throw new UnexpectedValueException();

      $this->Address = $Address;
  }

  // IAddressee
  private $Addressee = "";
  public function getAddressee():string
    { return $this->Addressee;}
  public function setAddressee(string $Addressee)
  {
     if (!is_string($Addressee))
          throw new UnexpectedValueException();

      $this->Addressee = $Addressee;
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
          case 'Address':
              return $this->getAddress();
          case 'Addressee':
              return $this->getAddressee();
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
          case 'Address':
              return $this->setAddress($value);
          case 'Addressee':
              return $this->setAddressee($value);
          default:
              $class = __CLASS__;
              throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
    }
}