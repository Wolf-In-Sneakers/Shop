<?php

session_start();

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\Order\Order;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$order = new Order($mysqli);

$errors = [];


if (isset($_POST['enter'])) {
    $login = $auth->login($_POST['login'], $_POST['password']);
    if (!empty($login["error"]))
        $errors[] = $login["error"];
} else if (!empty($_SESSION["user"])) {
    $id_user = $_SESSION["user"]["id_user"];

    if (isset($_POST["add_order"])) {
        $add_order = $order->add_order();
    }
}

require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/main/footer.php";
