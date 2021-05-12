<?php

session_start();

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\DB\DB;
use Shop\Product\Product;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$product = new Product($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (isset($_POST['enter'])) {
        $login = $auth->login($_POST['login'], $_POST['password']);
    } else if (!empty($_SESSION["user"])) {
        $id_user = $_SESSION["user"]['id_user'];

        if ((int)$_SESSION["user"]['id_access'] === 1) {
            if (isset($_POST['add_goods'])) {
                $add_goods = $product->add_goods($_POST['name'], $_POST['id_type'], $_POST['id_brand'], $_POST['price'], (int)$_POST['id_gender'], $_FILES['img']);
            } else if (isset($_POST["delete_goods"])) {
                $delete_goods = $product->delete_goods($_POST["id_product"]);
            }

            $goods_character = $product->goods_character();
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/product/read_goods_all.tmpl", [
        "user" => $_SESSION["user"],
        "UPLOAD_DIR" => UPLOAD_DIR,
        "UPLOAD_DIR_SMALL" => UPLOAD_DIR_SMALL,
        "errors" => $errors,
        "products" => $product->read_goods_all()["goods"],
        "goods_characters" => $goods_character["characters"]
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}
