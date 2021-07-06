<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;
use Shop\models\Order\Order;

class OrderController extends Controller
{
    private Order $order;

    public function __construct()
    {
        parent::__construct();

        $this->order = new Order();

    }

    public function indexAction()
    {
        try {
            if (isset($_POST['enter'])) {
                $login = $this->auth->login($_POST['login'], $_POST['password']);
            } else if (!empty($_SESSION["user"])) {
                $id_user = $_SESSION["user"]["id_user"];

                if (isset($_POST["add_order"])) {
                    $add_order = $this->order->addOrder();
                }
            }

        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }


        $path = "/order/addOrder.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
        ];

        $this->view->render($path, $vars);
    }

}