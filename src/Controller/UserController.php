<?php

namespace App\Controller;

use App\Model\User;
use PDO;
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
        $this->model= $model;
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
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
            }

            $resultSqlUser = $this->model->insertUser($username, $email, $password);
            if ($resultSqlUser) {
                $this->model->login($username, $password);

                return '';
            } else {
                $params['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
            }
        }
        return $template->render($params);
    }

    public function login(): string
    {
        $params = [];
        $template = $this->twig->load('security/loginUserPage.twig');
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                if (!$this->model->login($username, $password)) {
                     $params['errorMessage'] = 'Échec de connexion, veuillez réessayer.';
                } else {
                    return '';
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
        exit();
    }
}
