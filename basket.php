<?php

session_start();

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\Basket\Basket;
use Shop\DB\DB;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$basket = new Basket($mysqli);

$errors = [];


if (isset($_POST['enter'])) {
    $auth = $auth->login($_POST['login'], $_POST['password']);
    if (!empty($auth["error"]))
        $errors[] = $auth["error"];
} else if (isset($_POST["add_in_basket"])) {
    $add_in_basket = $basket->add_in_basket($_POST["id_product"]);
    if (!empty($add_in_basket["error"]))
        $errors[] = $add_in_basket["error"];
} else if (isset($_POST['change_quantity'])) {
    $change_quantity = $basket->change_quantity($_POST['id_product'], $_POST['value']);
    if (!empty($change_quantity["error"]))
        $errors[] = $change_quantity["error"];
} else if (isset($_POST['delete_in_basket'])) {
    $delete_in_basket = $basket->delete_in_basket($_POST["id_product"]);
    if (!empty($delete_in_basket["error"]))
        $errors[] = $delete_in_basket["error"];
} else if (isset($_POST['clear_basket'])) {
    $clear_basket = $basket->clear_basket();
    if (!empty($clear_basket["error"]))
        $errors[] = $clear_basket["error"];
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/basket/basket.php";
require_once "templates/main/footer.php";
