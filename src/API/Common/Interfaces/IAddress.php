<?php  
  namespace \API\Common;

interface IAddress
{
    function getAddress():string;
    function setAddress(string $Address);
}