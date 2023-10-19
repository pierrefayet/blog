<?php
// controllers/HomeController.php
namespace Pierre\Projet5Blog\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function homepage() : void
    {
        //$posts = getPosts();

        // Chargez et affichez le modèle Twig
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__  .'../Templates');
        $twig = new \Twig\Environment($loader);

        // Rendre le modèle "homepage.twig"
        $template = $twig->load("homePage.twig");
        echo $template->render(['posts' => 'tata']);
    }
}