<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;
use Shop\models\Comment\Comment;
use Shop\models\Product\Product;
use Shop\models\Product\ProductImg;

class ProductController extends Controller
{
    private Product $product;
    private ProductImg $product_img;
    private Comment $comment;

    public function __construct()
    {
        parent::__construct();

        $this->product = new Product();
        $this->product_img = new ProductImg();
        $this->comment = new Comment();
    }

    public function indexAction(int $id_product)
    {
        try {
            if (!empty($id_product)) {
                $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

                if (!empty($_SESSION["user"])) {
                    if ((int)$_SESSION["user"]['id_access'] === 1) {
                        if (isset($_POST['update_goods'])) {
                            $update_goods = $this->product->updateGoods($id_product, $_POST['name'], (int)$_POST['id_category'],
                                (int)$_POST['id_gender'], (int)$_POST['id_brand'], (int)$_POST['price'], $_FILES['img']);
                        } else if (isset($_POST["delete_goods"])) {
                            $delete_goods = $this->product->deleteGoods($id_product);
                            header("Location: /");
                        } else if (isset($_POST['set_main_img'])) {
                            $set_main_img = $this->product_img->setMainImg($id_product, $_POST['set_main_img']);
                        } else if (isset($_POST['delete_img'])) {
                            $delete_img = $this->product_img->deleteImg($id_product, $_POST['delete_img']);
                        } else if (isset($_POST['delete_comment'])) {
                            $delete_comment = $this->comment->deleteComment($id_product, $_POST['delete_comment']);
                        }

                        $goods_character = $this->product->goodsCharacter();
                    }

                }
                if (isset($_POST['add_comment'])) {
                    if (!empty($_SESSION["user"]))
                        $add_comment = $this->comment->addComment($id_product, $_SESSION["user"]["name"], $_POST['comment']);
                    else
                        $add_comment = $this->comment->addComment($id_product, $_POST['author'], $_POST['comment']);
                }
            } else {
                $id_product = -1;
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/product/readGoodsOne.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "IMG_NOT_FOUND" => IMG_NOT_FOUND,
            "goods" => $this->product->readGoodsOne($id_product)["goods"],
            "goods_characters" => $goods_character["characters"],
            "comments" => $this->comment->readComments($id_product)["comments"]
        ];

        $this->view->render($path, $vars);
    }

    public function readGoodsCategoryAction(int $id_category)
    {
        try {
            $id_category = (int)htmlspecialchars((int)strip_tags((int)$id_category));

            if (!empty($_SESSION["user"])) {
                if ((int)$_SESSION["user"]['id_access'] === 1) {
                    if (isset($_POST['add_goods'])) {
                        $add_goods = $this->product->addGoods($_POST['name'], $_POST['id_category'], $_POST['id_brand'], $_POST['price'], (int)$_POST['id_gender'], $_FILES['img']);
                    } else if (isset($_POST["delete_goods"])) {
                        $delete_goods = $this->product->deleteGoods($_POST["id_product"]);
                    }
                    $goods_character = $this->product->goodsCharacter();
                }
            }

            $read_goods_all_category = $this->product->readGoodsAllCategory($id_category);

        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        $path = "/product/readGoodsAll.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "products" => $read_goods_all_category["goods"],
            "goods_characters" => $goods_character["characters"]
        ];

        $this->view->render($path, $vars);
    }

    public function readGoodsFeaturedAction()
    {
        try {
            if (!empty($_SESSION["user"])) {
                if ((int)$_SESSION["user"]['id_access'] === 1) {
                    if (isset($_POST['add_goods'])) {
                        $add_goods = $this->product->addGoods($_POST['name'], $_POST['id_category'], $_POST['id_brand'], $_POST['price'], (int)$_POST['id_gender'], $_FILES['img']);
                    } else if (isset($_POST["delete_goods"])) {
                        $delete_goods = $this->product->deleteGoods($_POST["id_product"]);
                    }
                    $goods_character = $this->product->goodsCharacter();
                }
            }

            $read_goods_all_featured = $this->product->readGoodsAllFeatured();

        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        $path = "/product/readGoodsAll.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "products" => $read_goods_all_featured["goods"],
            "goods_characters" => $goods_character["characters"]
        ];

        $this->view->render($path, $vars);
    }

    public function searchAction()
    {
        try {
            $search = $this->product->search($_POST["search"]);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        $path = "/product/readGoodsAll.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "products" => $search["goods"]
        ];

        $this->view->render($path, $vars);
    }

    public function likeAction(int $id_product)
    {
        try {
            if (!empty($_SESSION["user"])) {
                $like = $this->product->like($id_product);

                http_response_code(200);
            } else {
                http_response_code(403);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode($e->getMessage());
        }
    }

}