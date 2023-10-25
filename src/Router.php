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
    public function routeRequest(string $page, string $controller): string
    {
        if (isset($this->routes[$controller][$page])) {
            var_dump($this->routes[$controller][$page]);
            if ($controller === 'post') {
                $model = new Post($this->pdo);
                $controller = new PostController($model);
                $action = $this->routes['post'][$page];
                return $controller->$action();
            }
        }

        // Je gére le cas où la route n'existe pas
        return 'echo Page non trouvée';
    }
}