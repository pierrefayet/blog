<?php

namespace App\Controller;

use App\Model\Comment;
use App\Service\CheckForm;
use App\Service\SecurityCsrf;
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
        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);
        if ($postId === null || $postId === false) {
            // Handle the error: postId is missing or is not a valid integer
        }
        return ($this->twig->load('comment/commentForm.html.twig')->render([
            'post' => $this->commentModel->getSingleComment($postId)
        ]));
    }

    public function indexComment(): string
    {
        $comments = $this->commentModel->getAllComments();

        return $this->twig->load('comment/listingComment.html.twig')->render(['comments' => $comments]);
    }

    public function addComment(): string
    {
        $params = [];
        $postId = $_GET['postId'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['role'])) {
                $params['unAuthorize'] = true;
                $params['errorMessage'] = 'Vous devez être connecté pour poster un commentaire.';
                return $this->twig->load('comment/commentForm.html.twig')->render($params);
            }

            if (false === SecurityCsrf::check($_POST)) {
                $params['errorMessage'] = 'Le token CSRF est invalide.';
                return $this->twig->load('comment/commentForm.html.twig')->render($params);
            }

            if (!empty($_POST['content'])) {
                $userId = $_SESSION['userId'];
                $content = $_POST['content'];
                if (!empty($errors)) {
                    $params['errorMessage'] = implode('<br>', $errors);
                }

                $result = $this->commentModel->insertComment($postId, $userId, $content);
                if ($result) {
                    $params['successMessage'] = 'Le commentaire a été ajouté avec succès.';
                } else {
                    $params['errorMessage'] = 'Une erreur est survenue lors de l\'ajout d\'un article.';
                }
            }

            $errors = CheckForm::checkFormCommentForm($content);
            if (!empty($errors)) {
                $params['errorMessage'] = implode('<br>', $errors);
                return $this->twig->load('comment/commentForm.html.twig')->render($params);
            }
        }

        return $this->twig->load('comment/commentForm.html.twig')->render($params);
    }

    public function handlerDeleteComment(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $commentId = $_GET['commentId'];
            if ($commentId) {
                // Appeler la méthode pour supprimer le commentaire
                $result = $this->commentModel->deleteComment($commentId);
                if ($result) {
                    $params['successMessage'] = 'Le statut du commentaire a été modifié avec succès.';
                    return $this->indexComment();
                } else {
                    $params['errorMessage'] = '
                    Une erreur est survenue lors de la modification du statut du commentaire.
                    ';
                }
            }
        }
        // J'affiche le listing mis à jour
        return $this->indexComment();
    }

    public function handlerApproveComment(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Récupérer l'ID du commentaire depuis les données POST ou GET
            $commentId = $_POST['commentId'] ?? $_GET['commentId'] ?? null;
            if ($commentId) {
                // Appeler la méthode pour modifier le statut du commentaire
                $result = $this->commentModel->modifyStatusComment($commentId);
                if ($result) {
                    $params['successMessage'] = 'Le statut du commentaire a été modifié avec succès.';
                    return $this->indexComment();
                } else {
                    $params['errorMessage'] = '
                    Une erreur est survenue lors de la modification du statut du commentaire.
                    ';
                }
            }
        }
        // J'affiche le listing mis à jour
        return $this->indexComment();
    }
}
