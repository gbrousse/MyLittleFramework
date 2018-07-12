<?php

error_reporting(E_ALL);

$autoloader = require_once 'vendor/autoload.php';



?>TEST WITH NO PARAMETERS<br/><?php
$loggerNoHandlerNoparams = new Core\Common\Logs\Logger();
$testObj1 = new \Core\Test\Test();
$testObj1->setLogger($loggerNoHandlerNoparams);
$testObj1->test1();
$testObj1->test12();
$testObj1->test2();
$loggerNoHandlerNoparams->common->show();

?><br/>TEST WITH level limit<br/><?php
$loggerNoHandler = new Core\Common\Logs\Logger(Psr\Log\LogLevel::ERROR,  Psr\Log\LogLevel::ERROR);
$testObj1 = new \Core\Test\Test();
$testObj1->setLogger($loggerNoHandler);
$testObj1->test1();
$testObj1->test12();
$testObj1->test2();
$loggerNoHandler->common->show();

?><br/>HANDLERS TEST MIN<br/><?php
$loggerHandlerMin = new Core\Common\Logs\Logger(Psr\Log\LogLevel::DEBUG,  Psr\Log\LogLevel::DEBUG);
$minHandler = new \Core\Common\Logs\handlers\CommonHandler();
$loggerHandlerMin->attachHandler($minHandler, 'minerror',  Psr\Log\LogLevel::ALERT);
$testObj1 = new \Core\Test\Test();
$testObj1->setLogger($loggerHandlerMin);
$testObj1->test1();
$testObj1->test12();
$testObj1->test2();
$loggerHandlerMin->minerror->show();

?><br/>HANDLERS TEST DEDICATE<br/><?php
$loggerHandlerDed = new Core\Common\Logs\Logger(Psr\Log\LogLevel::DEBUG,  Psr\Log\LogLevel::DEBUG);
$dedHandler = new \Core\Common\Logs\handlers\CommonHandler();
$loggerHandlerDed->attachHandler($dedHandler, 'dedtoerror',  Psr\Log\LogLevel::DEBUG, Psr\Log\LogLevel::ERROR);
$testObj1 = new \Core\Test\Test();
$testObj1->setLogger($loggerHandlerDed);
$testObj1->test1();
$testObj1->test12();
$testObj1->test2();
$loggerHandlerDed->dedtoerror->show();

?><br/>HANDLERS TEST MULTI<br/><?php
$loggerHandlerMulti = new Core\Common\Logs\Logger(Psr\Log\LogLevel::DEBUG,  Psr\Log\LogLevel::DEBUG);

$common = new \Core\Common\Logs\handlers\CommonHandler();
$loggerHandlerMulti->attachHandler($common, 'chandler');

$formatted = new \Core\Common\Logs\handlers\FormattedCommonHandler();
$loggerHandlerMulti->attachHandler($formatted, 'fhandler');

$file = new Core\Common\Logs\handlers\FileHandler('./logs');
print_r($file);
$loggerHandlerMulti->attachHandler($file, 'filehandler');

$fileEx = new Core\Common\Logs\handlers\ExtendFileHandler('./logs/extends');
$loggerHandlerMulti->attachHandler($fileEx, 'fileexhandler');

$testObj1 = new \Core\Test\Test();
$testObj1->setLogger($loggerHandlerMulti);
$testObj1->test1();
$testObj1->test12();
$testObj1->test2();
$loggerHandlerMulti->chandler->show();
$loggerHandlerMulti->fhandler->show();







