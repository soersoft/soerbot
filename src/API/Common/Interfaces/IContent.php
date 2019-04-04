<?php  
  namespace \API\Common;

interface IContent
{
    function getContent():JSON;
    function setContent(JSON $Content);
}