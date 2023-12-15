<?php

namespace App\Manager;

use App\Controller\AboutController;
use App\Controller\CommentController;
use App\Controller\HomePageController;
use App\Controller\NotFoundPageController;
use App\Controller\PostController;
use App\Controller\UserController;
use App\Model\Comment;
use App\Model\User;
use App\Model\Post;
use App\Service\Mailer;
use PDO;
use Twig\Environment;

class ControllerManager
{
    private PDO $pdo;
    private Environment $twig;

    /**
     * @param PDO $pdo
     * @param Environment $twig
     */
    public function __construct(PDO $pdo, Environment $twig)
    {
        $this->pdo = $pdo;
        $this->twig = $twig;
    }

    public function route(string $requestedController): object
    {
        if ($requestedController === 'HomePageController') {
            $mailer = new Mailer();
            $controller = new HomePageController($mailer, $this->twig);
        }

        if ($requestedController === 'AboutController') {
            $controller = new AboutController($this->twig);
        }

        if ($requestedController === 'PostController') {
            $model = new Post($this->pdo);
            $controller = new PostController($model, $this->twig);
        }

        if ($requestedController === 'UserController') {
            $model = new User($this->pdo);
            $controller = new UserController($model, $this->twig);
        }

        if ($requestedController === 'CommentController') {
            $model = new Comment($this->pdo);
            $controller = new CommentController($model, $this->twig);
        }

        if ($requestedController === 'NotfoundPageController') {
            $controller = new NotFoundPageController($this->twig);
        }

        if (!isset($controller)) {
            $controller = new NotFoundPageController($this->twig);
        }

        return $controller;
    }
}
