<?php  
namespace \API\Mail;

interface IContent
{
    function getContent():JSON;
    function setContent(JSON $Content);
}