<?php  
namespace API\Mail;

class Message implements IMessage
{
  // IAddress
  private $Header = "";
  public function getHeader():string
    { return $this->Header; }
  public function setHeader(string $Header)
  {
      if (!is_string($Header))
          throw new UnexpectedValueException();

      $this->Header = $Header;
  }

  // IContent
  /**
   * @val JSON
   */
  private $Content = "";
  public function getContent():string
    { return $this->Content; }
  public function setContent(string $Content)
  {
      if (!is_string($Content))
          throw new UnexpectedValueException();

      $this->Content = $Content;
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
          case 'Header':
              return $this->getHeader();
          case 'Content':
              return $this->getContent();
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
    public function __set(string $field, mixed $value)
    {
        switch( $field ) 
        {
          case 'Header':
              return $this->setHeader($value);
          case 'Content':
              return $this->setContent($value);
          default:
              $class = __CLASS__;
              throw new UnexpectedValueException( "Invalid property: {$class}->{$field}");
        }
    }
}