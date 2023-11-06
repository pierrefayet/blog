<?php

namespace App;

use App\Model\Post;
use PDO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Router
{
    private array $routes;
    private PDO $pdo;
    private Post $model;
    private Environment $twig;

    public function __construct(array $routes, PDO $pdo, Environment $twig,Post $model)
    {
        $this->routes = $routes;
        $this->pdo = $pdo;
        $this->twig = $twig;
        $this->model = $model;

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function routeRequest(string $page, string $controllerName): string
    {
        if (isset($this->routes[$controllerName])) {
            $actionName = $this->routes[$controllerName][$page] ?? null;
            var_dump('$actionName: ',$actionName);
            if ($actionName !== null) {
                $controllerClassName = "App\\Controller\\{$controllerName}";
                var_dump('$controllerClassName: ', $controllerClassName);
                if (class_exists($controllerClassName)) {
                    $controller = new $controllerClassName($this->model, $this->twig);
                    if (method_exists($controller, $actionName)) {
                        var_dump('0$controller->$actionName(): ', $controller->$actionName());
                        return $controller->$actionName();
                    }
                }
            }
        }

        http_response_code(404);
        return 'Page non trouvée';
    }
}