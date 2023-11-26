<?php

use App\config\DbConnect;
use App\Manager\ControllerManager;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';
Debugger::enable();
$dbConnect = new DbConnect('localhost', 'blog', 'nareendel', 'Aa19071985.');
$pdo = $dbConnect->getDb();

$loader = new FilesystemLoader('templates/');
$twig = new Environment($loader, ['debug' => true, 'strict_variables' => true]);
$twig->addExtension(new DebugExtension());
session_start();

$_SESSION['logged'] = isset($_SESSION['logged']);
$maxInactiveTime = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxInactiveTime)) {
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();
$twig->addGlobal('session', $_SESSION);
$method = $_GET['method'];
$requestedController = $_GET['controller'];
$controllerManager = new ControllerManager($pdo, $twig);
$controller = $controllerManager->route($requestedController);
$response = $controller->$method();
echo $response;