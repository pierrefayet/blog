<?php

namespace App\Controller;

use App\Model\Connection;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HomeConnectController
{
    private Connection $connectionUser;
    private ?string $errorMessage = null;
    private ?string $successMessage = null;

    public function __construct(Connection $connectionUser)
    {
        $this->connectionUser = $connectionUser;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function connect(): string
    {
        $loader = new FilesystemLoader('templates/');
        $twig = new Environment($loader);
        $template = $twig->load('pageConnect.twig');

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
            }

            $resultSqlUser = $this->connectionUser->modifyUser($email, $password);
            $params = [];
            if ($resultSqlUser) {
                $params ['successMessage'] = 'L\'utilisateur a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
            }

            return $template->render($params);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
            }

            $resultSqlUser = $this->connectionUser->deleteUser($userId);
            $params = [];
            if ($resultSqlUser) {
                $params ['successMessage'] = 'L\'utilisateur a été ajouté avec succès.';
            } else {
                $params ['errorMessage'] = 'Une erreur est survenue lors de l\'ajout de l\'utilisateur.';
            }

            return $template->render($params);
        }

        return $template->render();
    }
}
