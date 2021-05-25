<?php

// FRONT CONTROLLER

// Общие настройки
ini_set('display_errors',2);
error_reporting(E_ALL);

session_start();

// Автозагрузка классов
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');


// Вызов Router
$router = new Router();
$router->run();