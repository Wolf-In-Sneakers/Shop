<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;

class BasketController extends Controller
{
    public function indexAction()
    {
        $path = "/basket/basket.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"]
        ];

        $this->view->render($path, $vars);
    }

    public function addAction($id_product)
    {
        try {
            $add_in_basket = $this->basket->addInBasket($id_product);

            http_response_code(200);
            echo json_encode($add_in_basket);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode($e->getMessage());
        }
    }

    public function changeAction($id_product)
    {
        try {
            $change_quantity = $this->basket->changeQuantity($id_product, $_POST['quantity']);

            http_response_code(200);
            echo json_encode($change_quantity);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode($e->getMessage());
        }
    }

    public function deleteAction($id_product)
    {
        try {
            $delete_in_basket = $this->basket->deleteInBasket($id_product);

            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode($e->getMessage());

        }
    }

    public function clearAction()
    {
        try {
            $clear_basket = $this->basket->clearBasket();

            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode($e->getMessage());
        }
    }
}