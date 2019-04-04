<?php  
  namespace \API\Common;

interface IMail { 
    function getMailAddress():IMailAddress;
    function setMailAddress(IMailAddress $value);

    function getMessage():IMessage;
    function setMessage(IMessage $value);
}