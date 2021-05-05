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


if (isset($_POST['add_registration'])) {
    $registration = $auth->registration($_POST['login'], $_POST['password'], $_POST['check_password'], $_POST['name']);
    if (!empty($registration))
        $errors[] = $registration;
}

require_once "templates/main/header.php"; ?>

    <form action="registration.php" method="POST" class="wrapper regist flex-center">
        <a href="index.php" class="btn basket">Главная</a>
        <input type="text" name="name" class="input" placeholder="ИМЯ" required>
        <input type="text" name="login" class="input" placeholder="Логин" required>
        <input type="password" name="password" class="input" placeholder="Пароль" required>
        <input type="password" name="check_password" class="input" placeholder="Пароль" required>
        <input type="submit" name="add_registration" class="btn" value="Зарегестрироваться">
        <p>Уже есть аккаунт? <a href="login.php" class="delete-btn login">Войти</a>
        </p>
    </form>

<?php
require_once "templates/main/errors.php";
require_once "templates/main/footer.php";
