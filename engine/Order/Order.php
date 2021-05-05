<?php

namespace Shop\Order;

use mysqli;

class Order
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function add_order(): array
    {
        $message = [];
        $message["success"] = [];

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]["id_user"]));
        $basket = $_SESSION["basket"];

        $sql_query = "INSERT INTO orders(id_user) VALUES ($id_user);";
        if (!$this->mysqli->query($sql_query)) {
            $message["error"] = "Неудалось выполнить запрос на добавление товара в корзину!";
            return $message;
        }

        foreach ($basket as $goods) {
            $sql_query = "INSERT INTO orders_products(id_order, id_product, quantity) VALUES (" . $this->mysqli->insert_id . ", {$goods['id_product']}, {$goods['quantity']});";
            if (!$this->mysqli->query($sql_query)) {
                $message["error"] = "Неудалось выполнить запрос на добавление товара в корзину!";
                return $message;
            }
        }
    }
}
