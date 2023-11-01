<?php

namespace App;

use App\Controller\PostController;
use App\Model\Post;
use PDO;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Router
{
    private array $routes;
    private PDO $pdo;

    public function __construct(array $routes, PDO $pdo)
    {
        $this->routes = $routes;
        $this->pdo = $pdo;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function routeRequest(string $page, string $controllerName): string
    {
        var_dump('$page:',$page,'$controllerName:',  $controllerName);
        if (isset($this->routes[$controllerName])) {
            $actionName = $this->routes[$controllerName][$page] ?? null;
            if ($actionName !== null) {
                $controllerClassName = "App\\Controller\\{$controllerName}";
                if (class_exists($controllerClassName)) {
                    $model = new Post($this->pdo);
                    $controller = new $controllerClassName($model);
                    if (method_exists($controller, $actionName)) {
                        return $controller->$actionName();
                    }
                }
            }
        }

        // Si aucune correspondance n'est trouvée, retournez une réponse 404
        http_response_code(404);
        return 'Page non trouvée';
    }
}