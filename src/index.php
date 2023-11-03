<?php

use App\Model\DbConnect;
use App\Router;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

session_start();
$routes = include 'routes.php';
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();
Debugger::enable();
$loader = new FilesystemLoader('templates/');
$twig = new Environment($loader);
$router = new Router($routes, $pdo, $twig);
$page = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'HomePageController';
$response = $router->routeRequest($page, $controller, $twig);
echo $response;
