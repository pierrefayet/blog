<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Pierre\Projet5Blog\Router;
use Tracy\Debugger;

require_once '../vendor/autoload.php';

session_start();

Debugger::enable();

$routes = include 'routes.php';
$router = new Router($routes);
$page = $_GET['page'] ?? 'home';
$method = $_GET['method'] ?? 'DefaultMethod';
$response = $router->routeRequest($page, $method);
echo $response;