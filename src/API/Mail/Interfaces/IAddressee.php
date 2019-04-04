<?php  
namespace \API\Mail;

interface IAddressee
{
    function getAddressee():string;
    function setAddressee(string $Addressee);
}