<?php

session_start();

if (empty($_SESSION["user"])) {
    header('Location: /index.php');
}

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\User\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$user = new User($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (!empty($_SESSION["user"])) {
        $id_user = $_SESSION["user"]['id_user'];

        if (!empty($_POST["delete_acc"])) {
            $delete_acc = $user->delete_acc($_POST["passwd"]);
        }
    } else {
        header('Location: index.php');
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/profile/delete_acc.tmpl", [
        "user" => $_SESSION["user"],
        "errors" => $errors,
        "delete_acc" => $delete_acc
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}
