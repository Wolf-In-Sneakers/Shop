<?php

session_start();

if (!empty($_SESSION["user"])) {
    header('Location: index.php');
}

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];
try {
    if (isset($_POST['enter'])) {
        $login = $auth->login($_POST['login'], $_POST['password']);
        if ((isset($login['success'])) and ($login['success'] === true)) {
            header('Location: index.php');
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}


try {
    echo $template = $twig->render("/auth/login.tmpl", [
        "user" => $_SESSION["user"],
        "errors" => $errors,
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}