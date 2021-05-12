<?php

session_start();

if (empty($_SESSION["user"])) {
    header('Location: /index.php');
}

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\User\User;
use Shop\User\UserImg;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$user = new User($mysqli);
$user_img = new UserImg($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (!empty($_SESSION["user"])) {
        $id_user = $_SESSION["user"]['id_user'];

        if (!empty($_POST["update_profile"])) {
            $update_profile = $user->update_profile($_POST["name"], $_POST["login"], $_FILES["img"]);
        } else if (!empty($_POST["set_main_img_profile"])) {
            $set_main_img = $user_img->set_main_img($id_user, $_POST['set_main_img_profile']);
        } else if (!empty($_POST["delete_img_profile"])) {
            $delete_img = $user_img->delete_img($id_user, $_POST['delete_img_profile']);
        }

        $profile = $user->read_profile();

    } else {
        header('Location: index.php');
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/profile/profile.tmpl", [
        "user" => $profile["user"],
        "UPLOAD_DIR" => UPLOAD_DIR,
        "UPLOAD_DIR_SMALL" => UPLOAD_DIR_SMALL,
        "errors" => $errors,
        "imgs" => $profile["img"]
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}