<?php

namespace App;

use App\Controller\PageController;
use App\Controller\PostController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Router
{
    private array $routes;
    private PageController $pageController;
    private PostController $postController;

    public function __construct(array $routes, PageController $pageController, PostController $postController)
    {
        $this->routes = $routes;
        $this->pageController = $pageController;
        $this->postController = $postController;

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function routeRequest(string $page): string
    {
        if (isset($this->routes[$page])) {
            $this->pageController->displayPage($this->routes[$page]);
            if ($page === 'post') {
                $this->postController->addPost();
            }
        }

        // Je gére le cas où la route n'existe pas
        return 'echo Page non trouvée';
    }
}