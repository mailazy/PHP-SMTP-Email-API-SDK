<?php

define('MAILAZY_APIKEY','xxxxxxxxxxxxxxxxxxxxxxxx');// mailazy apikey replace at "MAILAZY_APIKEY"
define('MAILAZY_APISECRET','xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');// mailazy apikey replace at "MAILAZY_APISECRET"
define('MAILAZY_SERVICETYPE','api');// 'smtp';//api
//Load Composer's autoloader
require 'vendor/autoload.php';

$mailazy = new Mailazy();
$mailazyClient = $mailazy->setRequestType(MAILAZY_SERVICETYPE);
$mailazyClient->SMTPDebug = true;
$mailazyClient->setApikey(MAILAZY_APIKEY);
$mailazyClient->setApisecret(MAILAZY_APISECRET);
$mailazyClient->isHTML(true);
$mailazyClient->addAddress('username@example.com');
//Set CC address
//$mailazyClient->addBCC('username@example.com');
//Set BCC address
//$mailazyClient->addCC('username@example.com');
$mailazyClient->setSubject('Subject from here');
$mailazyClient->setBody('email message here');
$mailazyClient->setFrom('username@example.com');
//$mailazyClient->addReplyTo('username@example.com');
//$mailazyClient->addAttachment('hello.csv');
var_dump($mailazyClient->send());