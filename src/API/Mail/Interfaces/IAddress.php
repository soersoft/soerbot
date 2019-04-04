<?php  
namespace \API\Mail;

interface IAddress
{
    function getAddress():string;
    function setAddress(string $Address);
}