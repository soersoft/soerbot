<?php  
  namespace \API\Common;

interface IMail
{
    function getMailAddress():IMailAddress;
    function setMailAddress(IMailAddress $MailAddress);

    function getMessage():IMessage;
    function setMessage(IMessage $Message);
}