<?php

use App\Controller\AboutController;
use App\Controller\CommentController;
use App\Controller\HomePageController;
use App\Controller\ListingController;
use App\Controller\PostController;
use App\Controller\ShowPostController;
use App\Controller\UserLoginController;
use App\Controller\UserRegistrationController;
use App\Model\Connection;
use App\Model\DbConnect;
use App\Model\Post;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';
Debugger::enable();

$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();

$loader = new FilesystemLoader('templates/');
$twig = new Environment($loader);
$method = $_GET['method'] ?? 'home';
$requestedController = $_GET['controller'] ?? 'HomePageController';
$model = null;
$routes = include 'routes.php';
$controllerClassName = "App\\Controller\\{$requestedController}";

if ($requestedController === 'PostController') {
    $model = new Post($pdo);
    $controller = new PostController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'HomePageController') {
    $model = new Post($pdo);
    $controller = new HomePageController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'AboutController') {
    $model = new Post($pdo);
    $controller = new AboutController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'ListingController') {
    $model = new Post($pdo);
    $controller = new ListingController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'ShowPostController') {
    $model = new Post($pdo);
    $controller = new ShowPostController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'UserRegistrationController') {
    $model = new Connection($pdo);
    $controller = new UserRegistrationController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'UserLoginController') {
    $model = new Connection($pdo);
    $controller = new UserLoginController($model, $twig);
    $response = $controller->$method();
}

if ($requestedController === 'CommentController') {
    $model = new Comment($pdo);
    $controller = new CommentController($model, $twig);
    $response = $controller->$method();
}

session_start();
echo $response;