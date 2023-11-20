<?php

namespace App\Controller;

use App\Model\Post;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class UpdatePostController
{
    private Post $postModel; // On injecte le modèle
    private Environment $twig;

    public function __construct(Post $postModel, Environment $twig)
    {
        $this->postModel = $postModel; // on injecte le modèle dans le constructeur;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function update(): string
    {
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérez les données du formulaire
            $postId = $_POST['postId'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            // Utilisez le modèle pour mettre à jour le post
            $result = $this->postModel->modifyPost($postId, $title, $content);
            var_dump($result);

            if ($result) {
                $params ['successMessage'] = 'L\'article a été mis à jour avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de la mise à jour de l\'article.';
            }
        }

        return $this->twig->load('post/updatePost.twig')->render(context: ['postId' => $this->postModel->modifyPost($postId, $title, $content)]);
    }
}
