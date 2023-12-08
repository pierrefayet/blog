<?php

namespace App\Controller;

use App\Model\Comment;
use App\Model\Post;
use App\service\Security;
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
            // Je soumet le formulaire pour ajouter un post ici
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $intro = $_POST['intro'];
                $content = $_POST['content'];
                $author = $_POST['author'];
            }
            // J'utilise le modèle pour ajouter le post
            $result = $this->postModel->insertPost($title, $intro, $content, $author);
            if ($result) {
                $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
        }
        // J'affiche le formulaire d'ajout de post
        return $this->twig->load('post/post.twig')->render($params);
    }

    public function update(): string
    {
        $postId = $_GET['postId'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->twig->load('post/updatePost.twig')->render([
                'post' => $this->postModel->getSinglePost($postId)
            ]);
        }

        if (!SecurityCsrf::check($_POST)) {
            $params['errorMessage'] = 'Le token CSRF est invalide.';
            return $this->twig->load('post/updatePost.twig')->render([
                'post' => $this->postModel->getSinglePost($postId),
                'params' => $params
            ]);
        }

        $title = $_POST['title'];
        $intro = $_POST['intro'];
        $content = $_POST['content'];
        $author = $_POST['author'];
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
