<?php

session_start();

require_once "config/config.php";
require_once "vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\Comment\Comment;
use Shop\DB\DB;
use Shop\Product\Product;
use Shop\Product\ProductImg;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$product = new Product($mysqli);
$product_img = new ProductImg($mysqli);
$comment = new Comment($mysqli);

$errors = [];


if (!empty($_GET["id_product"])) {
    $id_product = (int)htmlspecialchars((int)strip_tags((int)$_GET["id_product"]));

    if (isset($_POST['enter'])) {
        $login = $auth->login($_POST['login'], $_POST['password']);
        if (!empty($login["error"]))
            $errors[] = $login["error"];
    } else if (!empty($_SESSION["user"])) {
        if ((int)$_SESSION["user"]['id_access'] === 1) {
            if (isset($_POST['update_goods'])) {
                $update_goods = $product->update_goods($id_product, $_POST['name'], (int)$_POST['id_type'], (int)$_POST['id_gender'], (int)$_POST['id_brand'], (int)$_POST['price'], $_FILES['img']);
                if (!empty($update_goods["error"]))
                    $errors[] = $update_goods["error"];
            } else if (isset($_POST["delete_goods"])) {
                $delete_goods = $product->delete_goods($id_product);
                if (empty($delete_goods["error"]))
                    header("Location: index.php");
                else
                    $errors[] = $delete_goods["error"];
            } else if (isset($_POST['set_main_img'])) {
                $set_main_img = $product_img->set_main_img($id_product, $_POST['set_main_img']);
                if (!empty($set_main_img["error"]))
                    $errors[] = $set_main_img["error"];
            } else if (isset($_POST['delete_img'])) {
                $delete_img = $product_img->delete_img($id_product, $_POST['delete_img']);
                if (!empty($delete_img["error"]))
                    $errors[] = $delete_img["error"];
            } else if (isset($_POST['delete_comment'])) {
                $delete_comment = $comment->delete_comment($id_product, $_POST['delete_comment']);
                if (!empty($delete_comment["error"]))
                    $errors[] = $delete_comment["error"];
            }
        }
        if (isset($_POST['like_product'])) {
            $like = $product->like($id_product);
            if (!empty($like["error"]))
                $errors[] = $like["error"];
        }
    }
    if (isset($_POST['add_comment'])) {
        if (!empty($_SESSION["user"]))
            $add_comment = $comment->add_comment($id_product, $_SESSION["user"]["name"], $_POST['comment']);
        else
            $add_comment = $comment->add_comment($id_product, $_POST['author'], $_POST['comment']);
        if (!empty($add_comment["error"]))
            $errors[] = $add_comment["error"];
    }
} else {
    echo "<h1>404 NOT FOUND</h1>";
    exit;
}


require_once "templates/main/header.php";
require_once "templates/main/auth.php";
require_once "templates/main/errors.php";
require_once "templates/product/read_goods_one.php";
require_once "templates/product/update_goods.php";
require_once "templates/comment/comments.php";
require_once "templates/main/footer.php";
