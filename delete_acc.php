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

spl_autoload_register(function ($className) {
    $className = str_replace("\\", "/", $className);
    require_once "engine/$className.php";
});

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$user = new User($mysqli);
$user_img = new UserImg($mysqli);

$errors = [];


if (empty($_SESSION["user"]))
    header('Location: index.php');
else {
    $id_user = $_SESSION["user"]['id_user'];

    if (!empty($_POST["delete_acc"])) {
        $delete_acc = $user->delete_acc($_POST["passwd"]);
        if (!empty($delete_acc["error"]))
            $errors[] = $delete_acc["error"];
    }
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/profile/delete_acc.php";
require_once "templates/main/footer.php";
