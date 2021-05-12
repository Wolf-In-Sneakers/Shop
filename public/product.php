<?php

session_start();

require_once "../vendor/autoload.php";

use Shop\Auth\Auth;
use Shop\Comment\Comment;
use Shop\DB\DB;
use Shop\Product\Product;
use Shop\Product\ProductImg;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$mysqli = DB::getInstance()->getConnection();
$auth = new Auth($mysqli);
$product = new Product($mysqli);
$product_img = new ProductImg($mysqli);
$comment = new Comment($mysqli);

$loader = new FilesystemLoader("../templates");
$twig = new Environment($loader);

$errors = [];

try {
    if (!empty($_GET["id_product"])) {
        $id_product = (int)htmlspecialchars((int)strip_tags((int)$_GET["id_product"]));

        if (isset($_POST['enter'])) {
            $login = $auth->login($_POST['login'], $_POST['password']);
        } else if (!empty($_SESSION["user"])) {
            if ((int)$_SESSION["user"]['id_access'] === 1) {
                if (isset($_POST['update_goods'])) {
                    $update_goods = $product->update_goods($id_product, $_POST['name'], (int)$_POST['id_type'],
                        (int)$_POST['id_gender'], (int)$_POST['id_brand'], (int)$_POST['price'], $_FILES['img']);
                } else if (isset($_POST["delete_goods"])) {
                    $delete_goods = $product->delete_goods($id_product);
                    header("Location: index.php");
                } else if (isset($_POST['set_main_img'])) {
                    $set_main_img = $product_img->set_main_img($id_product, $_POST['set_main_img']);
                } else if (isset($_POST['delete_img'])) {
                    $delete_img = $product_img->delete_img($id_product, $_POST['delete_img']);
                } else if (isset($_POST['delete_comment'])) {
                    $delete_comment = $comment->delete_comment($id_product, $_POST['delete_comment']);
                }

                $goods_character = $product->goods_character();
            }
            if (isset($_POST['like_product'])) {
                $like = $product->like($id_product);
            }
        }
        if (isset($_POST['add_comment'])) {
            if (!empty($_SESSION["user"]))
                $add_comment = $comment->add_comment($id_product, $_SESSION["user"]["name"], $_POST['comment']);
            else
                $add_comment = $comment->add_comment($id_product, $_POST['author'], $_POST['comment']);
        }
    } else {
        $id_product = -1;
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

try {
    echo $template = $twig->render("/product/read_goods_one.tmpl", [
        "user" => $_SESSION["user"],
        "UPLOAD_DIR" => UPLOAD_DIR,
        "UPLOAD_DIR_SMALL" => UPLOAD_DIR_SMALL,
        "errors" => $errors,
        "goods" => $product->read_goods_one($id_product)["goods"],
        "goods_characters" => $goods_character["characters"],
        "comments" => $comment->read_comments($id_product)["comments"]
    ]);
} catch (Exception $e) {
    echo $e->getMessage();
}