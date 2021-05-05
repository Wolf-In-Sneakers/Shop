<?php

session_start();

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\Product\Product;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$product = new Product($mysqli);

$errors = [];


if (isset($_POST['enter'])) {
    $login = $auth->login($_POST['login'], $_POST['password']);
    if (!empty($login["error"]))
        $errors[] = $login["error"];
} else if (!empty($_SESSION["user"])) {
    $id_user = $_SESSION["user"]['id_user'];

    if ((int)$_SESSION["user"]['id_access'] === 1) {
        if (isset($_POST['add_goods'])) {
            $add_goods = $product->add_goods($_POST['name'], $_POST['id_type'], $_POST['id_brand'], $_POST['price'], (int)$_POST['id_gender'], $_FILES['img']);
            if (!empty($add_goods["error"]))
                $errors[] = $add_goods["error"];
        } else if (isset($_POST["delete_goods"])) {
            $delete_goods = $product->delete_goods($_POST["id_product"]);
            if (!empty($delete_goods["error"]))
                $errors[] = $delete_goods["error"];
        }
    }
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/product/read_goods_all.php";
require_once "templates/product/add_goods.php";
require_once "templates/main/footer.php";
