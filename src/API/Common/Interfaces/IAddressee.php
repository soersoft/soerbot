<?php  
  namespace \API\Common;

interface IAddressee
{
    function getAddressee():string;
    function setAddressee(string $Addressee);
}