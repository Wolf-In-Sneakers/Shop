<?php

session_start();

if (empty($_SESSION["user"])) {
    header('Location: /index.php');
}

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\User\User;
use Shop\User\UserImg;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$user = new User($mysqli);
$user_img = new UserImg($mysqli);

$errors = [];


if (!empty($_SESSION["user"])) {
    $id_user = $_SESSION["user"]['id_user'];

    if (!empty($_POST["update_profile"])) {
        $update_profile = $user->update_profile($_POST["name"], $_POST["login"], $_FILES["img"]);
        if (!empty($update_profile["error"]))
            $errors[] = $update_profile["error"];
    } else if (!empty($_POST["set_main_img_profile"])) {
        $set_main_img = $user_img->set_main_img($id_user, $_POST['set_main_img_profile']);
        if (!empty($set_main_img["error"]))
            $errors[] = $set_main_img["error"];
    } else if (!empty($_POST["delete_img_profile"])) {
        $delete_img = $user_img->delete_img($id_user, $_POST['delete_img_profile']);
        if (!empty($delete_img["error"]))
            $errors[] = $delete_img["error"];
    }
} else {
    header('Location: index.php');
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/profile/profile.php";
require_once "templates/profile/update_profile.php";
require_once "templates/main/footer.php";
