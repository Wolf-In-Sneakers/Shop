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


if (empty($_SESSION["user"])) {
    header('Location: index.php');
} else {
    $id_user = $_SESSION["user"]['id_user'];

    if (isset($_POST["change_passwd"])) {
        $change_passwd = $user->change_passwd($_POST["last_passwd"], $_POST["passwd"], $_POST["passwd_check"]);
        if (!empty($change_passwd["error"]))
            $errors[] = $change_passwd["error"];
    }
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/profile/change_passwd.php";
require_once "templates/main/footer.php";
