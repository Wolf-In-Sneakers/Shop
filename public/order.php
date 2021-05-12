<?php

session_start();

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\Order\Order;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$order = new Order($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (isset($_POST['enter'])) {
        $login = $auth->login($_POST['login'], $_POST['password']);
    } else if (!empty($_SESSION["user"])) {
        $id_user = $_SESSION["user"]["id_user"];

        if (isset($_POST["add_order"])) {
            $add_order = $order->add_order();
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/order/add_order.tmpl", [
        "user" => $_SESSION["user"],
        "errors" => $errors,
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}