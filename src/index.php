<?php

use App\Model\DbConnect;
use App\Router;
use Tracy\Debugger;

require_once '../vendor/autoload.php';

session_start();
$routes = include 'routes.php';
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();
Debugger::enable();
$router = new Router($routes, $pdo);
$page = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'HomePageController';
$response = $router->routeRequest($page, $controller);
echo $response;
