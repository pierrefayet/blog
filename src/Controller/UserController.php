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
                $params ['successMessage'] = 'L\'utilisateur a été ajouté avec succès.';
                $_SESSION['logged'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('Location:http://localhost:8080/src/index.php?method=home&controller=HomePageController');
                return $template->render($params);
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
            }
        }
        return $template->render($params);
    }

    public function login(): string
    {
        $params = [];
        $template = $this->twig->load('security/loginUserPage.twig');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $user = $this->model->checkUser($username, $password);

                if ($user) {
                    $_SESSION['logged'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['isConnected'] = true;
                    $params['successMessage'] = "Connexion réussie, bienvenue $username.";
                    header('Location: http://localhost:8080/src/index.php?method=home&controller=HomePageController');
                    exit();
                } else {
                    $params['errorMessage'] = 'Échec de connexion, veuillez réessayer.';
                }
            } else {
                $params['errorMessage'] = 'Veuillez remplir tous les champs.';
            }
        }

        return $template->render($params);
    }

    public function logout(): string
    {
        session_unset();
        session_destroy();
        $_SESSION['logout_message'] = 'Vous avez été déconnecté avec succès.';
        header('Location: http://localhost:8080/src/index.php?method=home&controller=HomePageController');
        exit;
    }
}
