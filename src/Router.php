<?php

namespace App;

use App\Controller\PostController;
use App\Model\Post;
use PDO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Router
{
    private array $routes;
    private PDO $pdo;

    private Environment $twig;

    public function __construct(array $routes, PDO $pdo, Environment $twig )
    {
        $this->routes = $routes;
        $this->pdo = $pdo;
        $this->twig = $twig ;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function routeRequest(string $page, string $controllerName, Environment $twig): string
    {
        if (isset($this->routes[$controllerName])) {
            $actionName = $this->routes[$controllerName][$page] ?? null;
            if ($actionName !== null) {
                $controllerClassName = "App\\Controller\\{$controllerName}";
                if (class_exists($controllerClassName)) {
                    $model = new Post($this->pdo);
                    $controller = new $controllerClassName($model, $twig);
                    if (method_exists($controller, $actionName)) {
                        return $controller->$actionName();
                    }
                }
            }
        }

        http_response_code(404);
        return 'Page non trouvée';
    }
}