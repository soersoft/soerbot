<?php  
  namespace \API\Common;

interface IAddressee { 
    function get():string;
    function set(string $value);
}