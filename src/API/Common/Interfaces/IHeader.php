<?php  
  namespace \API\Common;

interface IHeader
{
    function getHeader():string;
    function setHeader(string $Header);
}