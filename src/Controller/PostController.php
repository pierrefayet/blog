<?php

namespace App\Controller;

use App\Model\Post;
use App\service\CheckForm;
use App\service\SecurityCsrf;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController
{
    private Post $postModel;
    private Environment $twig;

    public function __construct(Post $postModel, Environment $twig)
    {
        $this->postModel = $postModel;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function show(): string
    {
        $postId = $_GET['postId'];
        return ($this->twig->load('post/show.html.twig')->render([
            'post' => $this->postModel->getSinglePost($postId),
            'commentsByPost' => $this->postModel->getAllComments($postId)
        ]));
    }

    public function index(): string
    {
        $posts = $this->postModel->getAllPosts();
        return $this->twig->load('post/listing.twig')->render(['posts' => $posts]);
    }

    public function addPost(): string
    {
        $params = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (false === SecurityCsrf::check($_POST)) {
                $params['errorMessage'] = 'Le token CSRF est invalide.';
                return $this->twig->load('post/post.twig')->render(
                    $params
                );
            }
            if (isset($_POST['title']) && isset($_POST['intro']) && isset($_POST['content']) && isset($_POST['author'])) {
                $title = $_POST['title'];
                $intro = $_POST['intro'];
                $content = $_POST['content'];
                $author = $_POST['author'];

            }

            $errors = CheckForm::checkFormPostForm($title, $intro, $content, $author);
            if (!empty($errors)) {
                $params['errors'] = $errors;
                return $this->twig->load('post/post.twig')->render($params);
            }

            $result = $this->postModel->insertPost($title, $intro, $content, $author);
            if ($result) {
                $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
        }

        return $this->twig->load('post/post.twig')->render($params);
    }

    public function update(): string
    {
        $postId = $_GET['postId'];
        $params = [];
        $errorMessages = [];
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->twig->load('post/updatePost.twig')->render([
                'post' => $this->postModel->getSinglePost($postId)
            ]);
        }

        if (!SecurityCsrf::check($_POST)) {
            $errorMessages[] = 'Le token CSRF est invalide.';
        }

        $title = $_POST['title'];
        $intro = $_POST['intro'];
        $content = $_POST['content'];
        $author = $_POST['author'];
        $errors = CheckForm::checkFormPostForm($title, $intro, $content, $author);
        if (!empty($errors)) {
            $errorMessages = array_merge($errorMessages, $errors);
        }

        if (!empty($errorMessages)) {
            $params['errorMessages'] = $errorMessages;
            return $this->twig->load('post/updatePost.twig')->render(array_merge([
                'post' => $this->postModel->getSinglePost($postId)
            ], $params));
        }

        $result = $this->postModel->modifyPost($title, $intro,  $content, $postId, $author);
        if ($result) {
            $params['successMessage'] = 'L\'article a été mis à jour avec succès.';
            $posts = $this->postModel->getAllPosts();

            return $this->twig->load('post/listing.twig')->render(['posts' => $posts, 'params' => $params]);
        } else {
            $params['errorMessage'] = 'Une erreur est survenue lors de la mise à jour de l\'article.';
        }

        return $this->twig->load('post/updatePost.twig')->render([
            'post' => $this->postModel->getSinglePost($postId),
            'params' => $params
        ]);
    }

    public function deletePost(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $postId = $_GET['postId'];
            $this->postModel->deleteAllComment($postId);
            $this->postModel->deletePost($postId);
        }

        return $this->twig->load('post/listing.twig')->render(['posts' => $this->postModel->getAllPosts()]);
    }
}
