<?php  
  namespace \API\Common;

interface IAddress { 
    function get():string;
    function set(string $value);
}