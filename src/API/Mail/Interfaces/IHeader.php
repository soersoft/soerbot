<?php  
namespace \API\Mail;

interface IHeader
{
    function getHeader():string;
    function setHeader(string $Header);
}