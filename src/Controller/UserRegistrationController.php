<?php

namespace App\Controller;

use App\Model\Connection;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserRegistrationController
{
    private Connection $connectionUser;
    private Environment $twig;

    public function __construct(Connection $connectionUser,Environment $twig)
    {
        $this->connectionUser = $connectionUser;
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function addUser(): string
    {

        $template = $this->twig->load('security/registrationUserPage.twig')->render();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
            }

            $resultSqlUser = $this->connectionUser->insertUser($email, $password);
            $params = [];
            if ($resultSqlUser) {
                $params ['successMessage'] = 'L\'utilisateur a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
            }

            return $template->render($params);
        }

        return $template;
    }
}
