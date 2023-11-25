<?php

namespace App\Controller;

use App\Model\Comment;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class CommentController
{
    private Environment $twig;
    private Comment $commentModel;


    public function __construct(Comment $commentModel, Environment $twig)
    {
        $this->commentModel = $commentModel;
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function show(): string
    {
        $postId = $_GET['postId'];
        return($this->twig->load('comment/commentForm.html.twig')->render(['post' => $this->commentModel->getSingleComment($postId)]));
    }

    public function indexComment(): string
    {
        $commentId = $_GET['commentId'];
        return $this->twig->load('comment/listingComment.html.twig')->render(['comment' => $this->commentModel->getSingleComment($commentId)]);
    }

    public function addComment(): string
    {
        $params = [];
        $postId = $_GET['postId'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Je soumet le formulaire pour ajouter un commentaire ici
            if (isset($_SESSION['userId']) && !empty($_POST['content'])) {
                $userId = $_SESSION['userId'];
                $content = $_POST['content'];
            }

            // J'utilise le modèle pour ajouter le commentaire
            $result = $this->commentModel->insertComment($postId, $userId , $content);
            var_dump('ici',$result);
            if ($result) {
                $params ['successMessage'] = 'Le commentaire a été ajouté avec succès.';
                header("Location: index.php?method=show&controller=CommentController&postId={$postId}");
                exit();
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout du commentaire.';
            }
        }

        // J'affiche le formulaire d'ajout du commentaire
        return $this->twig->load('comment/commentForm.html.twig')->render($params);
    }

    public function updateComment(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérez les données du formulaire
            $content = $_POST['content'];
            $author = $_POST['content'];
            $commentId = $_GET['commentId'];


            // Utilisez le modèle pour mettre à jour le commentaire
            $result = $this->commentModel->modifyComment($author, $content);
            if ($result) {
                $params ['successMessage'] = 'Le commentaire a été mis à jour avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de la mise à jour du commentaire.';
            }
        }

        $commentId = $_GET['commentId'];
        return $this->twig->load('comment/updateComment.html.twig')->render(['comment' => $this->commentModel->getSingleComment($commentId), 'params' => $params]);
    }

    public function deleteComment(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = [];
            $commentId = $_GET['commentId'];
            var_dump($commentId);
            // J'utilise le modèle pour ajouter le commentaire
            $result = $this->commentModel->deleteComment($commentId);
            if ($result) {
                $params ['successMessage'] = 'Le commentaire a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout du commentaire.';
            }
        }
        // J'affiche le formulaire d'ajout du commentaire
        return $this->twig->load('comment/listingComment.html.twig')->render(['comment' => $this->commentModel->getAllComments(), 'params' => $params]);
    }
}