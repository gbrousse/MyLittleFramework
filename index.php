<?php
error_reporting(E_ALL);

use Core\Common\Container\ServiceLocator;
use Core\Common\Logs\Logger;
use Core\Common\Conf\Config;
use Core\Common\Db\DbPDO;
use Core\Router\Router;
use Core\Models\ConnectedUserModel;


session_start();



$autoloader = require_once 'vendor/autoload.php';
/*$autoloader->loadClass("Core\Common\container\ServiceLocator");

print_r($autoloader);
exit(2);*/



// Chargement du conteneur de services
$appServices = new ServiceLocator();

//chargement de la conf
$config = new config ('./src/conf/config.ini');

// Chargement et initialisation du logger
$logger = new Logger($config->logs->path, array(
    'lvl'   =>  $config->logs->level,
    'mod'   =>  $config->logs->mod
));

// Initialisation de la bdd
$db = new DbPDO($config->bdd, $logger);

// Chargement de l'utilisateur
$user = new ConnectedUserModel($db, $logger);


// Initialisation du router
$router = new router($config->routes,$logger);

// Ajout des éléments au conteneur de services
$appServices->add('config', $config);
$appServices->add('logger', $logger);
$appServices->add('db', $db);
$appServices->add('user', $user);
$appServices->add('router', $router);



$controllerSetup = $router->getController();
$controllerClass = '\\Core\\Controllers\\'.ucfirst($controllerSetup['controller']).'Controller';
$controller = new $controllerClass($controllerSetup['options'],$appServices);
