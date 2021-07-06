<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;
use Shop\models\Product\Product;

class MainController extends Controller
{
    private Product $product;

    public function __construct()
    {
        parent::__construct();

        $this->product = new Product();
    }

    public function indexAction()
    {
        try {
            $read_goods_all_featured = $this->product->readGoodsAllFeatured();
            $read_goods_all_featured["goods"] = array_slice($read_goods_all_featured["goods"], 0, 8);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        $path = "/index/index.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "products" => $read_goods_all_featured["goods"]
        ];

        $this->view->render($path, $vars);
    }

}