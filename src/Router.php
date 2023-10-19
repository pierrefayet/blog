<?php

namespace Pierre\Projet5Blog;
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
    $this->routes = $routes;
    }

    public function routeRequest($page, $method)
    {
        if (isset($this->routes[$page][$method])) {
        list($controllerName, $methodName) = explode('@', $this->routes[$page][$method]);
        $controllerClass = 'App\Controller\\' . ucfirst(strtolower($controllerName)) . 'Controller';

        if (class_exists($controllerClass) && method_exists($controllerClass, $methodName)) {
        $controller = new $controllerClass();
            return call_user_func([$controller, $methodName]);
        }
    }

    // Gérer le cas où la route n'existe pas
    return 'echo Page non trouvée';
    }
}