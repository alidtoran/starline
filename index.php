<?php
session_start();

error_reporting(E_ALL); 
ini_set("display_errors", 1); 

define('DS', DIRECTORY_SEPARATOR);

require_once('libs/Controller.php');
require_once('libs/Model.php');

require_once('controllers/MainController.php');
require_once('models/User.php');

$route = isset($_GET['r']) ? explode('/', trim($_GET['r'], '/')) : array();

$Controller = new MainController();

$Controller->baseUrl = '';
$Controller->basePath = dirname(__FILE__);
$Controller->viewPath = $Controller->basePath . DS . 'views' . DS;

$Controller->run($route);
