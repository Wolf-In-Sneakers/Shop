<?php


namespace Shop\core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader("../templates");
        $this->twig = new Environment($loader);
    }

    public function render(string $path, array $vars = [])
    {
        try {
            echo $template = $this->twig->render($path, $vars);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}