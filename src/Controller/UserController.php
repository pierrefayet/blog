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

            $resultSqlUser = $this->model->insertUser($username, $email, $password,);
            if ($resultSqlUser) {
                $params ['successMessage'] = 'L\'utilisateur a été ajouté avec succès.';
                $_SESSION['logged'] = true;
                $_SESSION['username'] = $username;
                header('Location:http://localhost:8080/src/index.php?method=home&controller=HomePageController');
                exit();
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
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
            }

            $user= $this->model->checkUser($username,$email, $password);
            if ($user) {
                var_dump('ici');
                $_SESSION['logged'] = true;
                $_SESSION['username'] = $username;
                header('Location:http://localhost:8080/src/index.php?method=home&controller=HomePageController');
                exit();
                $params ['successMessage'] = "Connexion réussi, bienvenue $username.";
            } else {
                $params ['errorMessage'] = '&Eacute;chec de connexion, veuillez rééssayer.';
            }
        }

        return $template->render($params);
    }

    public function logout(): string
    {
        $params = [];
        $template = $this->twig->load('security/loginUserPage.twig');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $userName = $_POST['user_name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
            }
        }

        return $template->render($params);
    }
}
