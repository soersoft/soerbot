<?php  
namespace \API\Mail;

interface IMail
{
    function getMailAddressSender():IMailAddress;
    function setMailAddressSender(IMailAddress $MailAddress);

    function getMailAddressReciever():IMailAddress;
    function setMailAddressReciever(IMailAddress $MailAddress);

    function getMessage():IMessage;
    function setMessage(IMessage $Message);
}