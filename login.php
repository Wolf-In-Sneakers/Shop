<?php

session_start();

if (!empty($_SESSION["user"])) {
    header('Location: index.php');
}

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);

$errors = [];

if (isset($_POST['enter'])) {
    $login = $auth->login($_POST['login'], $_POST['password']);
    if ((isset($login['success'])) and ($login['success'] === true)) {
        header('Location: index.php');
    } else if (!empty($login["error"])) {
        $errors[] = $login["error"];
    }
}

require_once "templates/main/header.php"; ?>

    <form action='login.php' method='POST' class='wrapper flex-center regist'>
        <a href="index.php" class="btn basket">Главная</a>
        <input type="text" name='login' class="input" placeholder='Логин' required>
        <input type="password" name='password' class="input" placeholder='Пароль' required>
        <div>
            <input type="submit" name='enter' class="btn" value='Войти'>
            <a href='registration.php' class='btn'>Регистрация</a>
        </div>
    </form>

<?php
require_once "templates/main/errors.php";
require_once "templates/main/footer.php";
