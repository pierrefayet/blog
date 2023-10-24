<?php

use App\Controller\PageController;
use App\Controller\PostController;
use App\Controller\DisplayArticleController;
use App\Model\DbConnect;
use App\Model\InsertPost;
use App\Model\GetAllPosts;
use App\Router;
use Tracy\Debugger;

require_once '../vendor/autoload.php';

session_start();
$routes = include 'routes.php';
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();
Debugger::enable();
$insertPost = new InsertPost($pdo);
$displayArticleController =  new DisplayArticleController($pdo);
$router = new Router($routes, new PageController(), new PostController($insertPost));
$page = $_GET['page'] ?? 'home';
$response = $router->routeRequest($page);
echo $response;