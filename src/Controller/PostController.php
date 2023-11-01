<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class PostController
{
    private Post $postModel; // On injecte le modèle
    private ?string $errorMessage = null;
    private ?string $successMessage = null;


    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function add(): string
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);

        $template = $twig->load('post.twig');

        // Je soumet le formulaire pour ajouter un post ici
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
            }
            // J'utilise le modèle pour ajouter le post
                $result = $this->postModel->insertPost($title, $content);
                $params = [];
            if ($result) {
               $params ['successMessage'] = 'L\'article a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
            // J'affiche le formulaire d'ajout de post

            return $template->render($params);
        }

        return $template->render();
    }
}
