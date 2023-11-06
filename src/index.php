<?php

use App\Model\DbConnect;
use App\Model\Post;
use App\Router;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';
$routes = include 'routes.php';
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();
$loader = new FilesystemLoader('templates/');
$twig = new Environment($loader);
$model = new Post($pdo);
$router = new Router($routes, $pdo, $twig, $model);

Debugger::enable();
session_start();
$page = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'HomePageController';
$controllerClassName = $router->routeRequest($page, $controller);
$response = $router->routeRequest($page, $controller, $twig);

echo $response;
