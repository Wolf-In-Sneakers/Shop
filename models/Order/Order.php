<?php

namespace Shop\models\Order;

use Shop\core\Model;

class Order extends Model // НЕ РАБОТАЕТ
{

    public function addOrder(): array
    {
        $message["success"] = [];

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]["id_user"]));
        $basket = $_SESSION["basket"];

        $sql_query = "INSERT INTO orders(id_user) VALUES (?);";
        $this->db->query($sql_query, [$id_user]);

        foreach ($basket as $goods) {
            $sql_query = "INSERT INTO orders_products(id_order, id_product, quantity) VALUES (LAST_INSERT_ID(), ?, ?);";
            $this->db->query($sql_query, [$goods['id_product'], $goods['quantity']]);
        }

        $message["success"][] = "Заказ успешно добавлен!";

        return $message;
    }

}
