<?php

session_start();

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\Basket\Basket;
use Shop\DB\DB;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$basket = new Basket($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (isset($_POST['enter'])) {
        $auth = $auth->login($_POST['login'], $_POST['password']);
    } else if (isset($_POST["add_in_basket"])) {
        $add_in_basket = $basket->add_in_basket($_POST["id_product"]);
    } else if (isset($_POST['change_quantity'])) {
        $change_quantity = $basket->change_quantity($_POST['id_product'], $_POST['value']);
    } else if (isset($_POST['delete_in_basket'])) {
        $delete_in_basket = $basket->delete_in_basket($_POST["id_product"]);
    } else if (isset($_POST['clear_basket'])) {
        $clear_basket = $basket->clear_basket();
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/basket/basket.tmpl", [
        "user" => $_SESSION["user"],
        "basket" => $_SESSION["basket"],
        "errors" => $errors,
        "UPLOAD_DIR" => UPLOAD_DIR,
        "UPLOAD_DIR_SMALL" => UPLOAD_DIR_SMALL,
        "basket_total" => $basket->basket_total()
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}

