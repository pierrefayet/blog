<?php

namespace App\Controller;

use App\Model\User;
use App\Service\CheckForm;
use App\Service\SecurityCsrf;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController
{
    private Environment $twig;
    private User $model;

    public function __construct(User $model, Environment $twig)
    {
        $this->twig = $twig;
        $this->model = $model;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function register(): string
    {
        $template = $this->twig->load('security/registerUserPage.twig');
        $params = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (SecurityCsrf::check($_POST)) {
                if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $errors = CheckForm::checkFormRegister($username, $email, $password);
                }

                if ($this->model->userExists($username, $email)) {
                    $params['errorMessage'] = 'Cet utilisateur existe déjà.';
                    return $template->render($params);
                }

                if (!empty($errors)) {
                    $params['errorMessage'] = implode('<br>', $errors);
                    return $template->render($params);
                }

                $resultSqlUser = $this->model->insertUser($username, $email, $password);
                if ($resultSqlUser && $this->model->login($username, $password)) {
                    return '';
                } else {
                    $params['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
                }
            } else {
                $params['errorMessage'] = 'Token CSRF invalide';
            }
        }

        return $template->render($params);
    }

    public function login(): string
    {
        $params = [];
        $template = $this->twig->load('security/loginUserPage.twig');
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (SecurityCsrf::check($_POST)) {
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $errors = CheckForm::checkFormLogin($username, $password);

                    if (!empty($errors)) {
                        $params['errorMessage'] = implode('<br>', $errors);
                    }

                    if (!$this->model->login($username, $password)) {
                        $params['errorMessage'] = '
                     Échec de connexion, si vous n\'avez pas de compte veuillez vous inscrire, sinon veuillez réessayer.
                     ';
                    }
                } else {
                    $params['errorMessage'] = 'Token CSRF invalide';
                }
            }
        }

        return $template->render($params);
    }

    public function logout(): string
    {
        $_SESSION['logged'] = false;
        $_SESSION = [];
        session_destroy();
        header('Location: http://localhost:8080/src/index.php?method=home&controller=HomePageController');
        return '';
    }

    public function update(): string
    {
        $params = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SecurityCsrf::check($_POST)) {
                $params['errorMessage'] = 'Token CSRF invalide';
            } else {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $userId = intval($_SESSION['userId']);
                $result = $this->model->modifyUser($username, $email, $password, $userId);
                if ($result) {
                    $params['successMessage'] = 'L\'article a été mis à jour avec succès.';
                } else {
                    $params['errorMessage'] = 'Une erreur est survenue lors de la mise à jour de l\'article.';
                }
            }
        }

        return $this->twig->load('security/updateUserPage.html.twig')->render($params);
    }
}
