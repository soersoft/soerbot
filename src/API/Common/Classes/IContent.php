<?php  
  namespace \API\Common;

interface IHeader { 
    function get():string;
    function set(string $value);
}