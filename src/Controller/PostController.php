<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PostController
{
    private Post $model; // On injecte le modèle
    private Environment $twig;


    public function __construct(Post $model, Environment $twig)
    {
        $this->model = $model; // on injecte le modèle dans le constructeur;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function addPost(): string
    {
        $params = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Je soumet le formulaire pour ajouter un post ici
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
            }
            // J'utilise le modèle pour ajouter le post
            $result = $this->model->insertPost($title, $content);
            if ($result) {
                $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
        }
        // J'affiche le formulaire d'ajout de post
        return $this->twig->load('post/post.twig')->render($params);
    }

    public function deletePost(): string
    {
        $params = [];
        // Je soumet le formulaire pour ajouter un post ici
        if (isset($_POST['title']) && isset($_POST['content'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];
        }
        // J'utilise le modèle pour ajouter le post
        $result = $this->model->deletePost($title, $content);;

        if ($result) {
            $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
        } else {
            $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
        }
        // J'affiche le formulaire d'ajout de post
        return $this->twig->load('post/post.twig')->render($params);
    }
}
