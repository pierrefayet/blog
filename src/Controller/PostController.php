<?php

namespace App\Controller;

use App\Model\InsertPost;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class PostController
{
    private InsertPost $postModel; // On injecte le modèle
    private ?string $errorMessage = null;
    private ?string $successMessage = null;


    public function __construct(InsertPost $postModel)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function addPost(): void
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        // Je soumet le formulaire pour ajouter un post ici
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // J'utilise le modèle pour ajouter le post
                $result = $this->postModel->insertPost($title, $content);
            if ($result) {
                var_dump('un result trouvé');
                $this->successMessage = 'L\'article a été ajouté avec succès.';
            } else {
                var_dump('pas de result trouvé');
                $this->errorMessage = 'Une erreur est survenue lors de l\'ajout de l\'article.';
            }
            var_dump('on passe ici');
            // J'affiche le formulaire d'ajout de post
            $loader = new FilesystemLoader('templates/');
            $twig = new Environment($loader);

            $template = $twig->load('post.twig');
            echo $template->render([
                'successMessage' => $this->successMessage,
                'errorMessage' => $this->errorMessage,
            ]);
        }
    }
}