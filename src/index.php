<?php

use App\config\DbConnect;
use App\Manager\ControllerManager;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';
Debugger::enable();
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();

$loader = new FilesystemLoader('templates/');
$twig = new Environment($loader);
$method = $_GET['method'];
$requestedController = $_GET['controller'];
$controllerManager = new ControllerManager($pdo, $twig);
$controller = $controllerManager->route($requestedController);
$response = $controller->$method();

echo $response;