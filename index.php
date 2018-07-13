<?php
error_reporting(E_ALL);

use Core\Common\Container\ServiceLocator;
use Core\Common\Logs\Logger;
use Core\Common\Conf\Config;
use Core\Common\Db\DbPDO;
use Core\Router\Router;
use Psr\Log\LogLevel;
use Core\Common\Logs\handlers\FileHandler;

session_start();



$autoloader = require_once 'vendor/autoload.php';

// Chargement du conteneur de services
$appServices = new ServiceLocator();

//chargement de la conf
$config = new config ('./src/conf/config.ini');

// Chargement et initialisation du logger
$logger = new Logger($config->logs->level, $config->logs->backtrace);
$fileHandler = new FileHandler($config->logs->path);
$logger->attachHandler($fileHandler,'logfile');

// Initialisation de la bdd
$db = new DbPDO($config->bdd, $logger);

// Initialisation du router
$router = new router($config->routes,$logger);

// Ajout des éléments au conteneur de services
$appServices->add('config', $config);
$appServices->add('logger', $logger);
$appServices->add('db', $db);
$appServices->add('router', $router);



$controllerSetup = $router->getController();
$controllerClass = '\\Core\\Controllers\\'.ucfirst($controllerSetup['controller']).'Controller';
$controller = new $controllerClass($controllerSetup['options'],$appServices);
