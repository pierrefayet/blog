<?php

namespace App\Manager;

use App\Controller\AboutController;
use App\Controller\CommentController;
use App\Controller\HomePageController;
use App\Controller\ListingController;
use App\Controller\PostController;
use App\Controller\ShowPostController;
use App\Controller\UserController;
use App\Controller\UserRegistrationController;
use App\Model\Comment;
use App\Model\User;
use App\Model\Post;
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

    public function route($requestedController): object
    {

        if ($requestedController === 'PostController') {
            $model = new Post($this->pdo);
            $controller = new PostController($model, $this->twig);
        }

        if ($requestedController === 'HomePageController') {
            $model = new Post($this->pdo);
            $controller = new HomePageController($model, $this->twig);
        }

        if ($requestedController === 'AboutController') {
            $model = new Post($this->pdo);
            $controller = new AboutController($model, $this->twig);
        }

        if ($requestedController === 'ListingController') {
            $model = new Post($this->pdo);
            $controller = new ListingController($model, $this->twig);
        }

        if ($requestedController === 'ShowPostController') {
            $model = new Post($this->pdo);
            $controller = new ShowPostController($model, $this->twig);
        }

        if ($requestedController === 'UserRegistrationController') {
            $model = new User($this->pdo);
            $controller = new UserRegistrationController($model, $this->twig);
        }

        if ($requestedController === 'UserController') {
            $model = new User($this->pdo);
            $controller = new UserController($model, $this->twig);
        }

        if ($requestedController === 'CommentController') {
            $model = new Comment($this->pdo);
            $controller = new CommentController($model, $this->twig);
        }

        return $controller;
    }
}