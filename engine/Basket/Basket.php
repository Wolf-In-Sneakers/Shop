<?php

namespace Shop\Basket;

use Exception;
use mysqli;

class Basket
{
    private mysqli $mysqli;
    private int $id_user;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->id_user = (!empty($_SESSION["user"])) ? (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]["id_user"])) : 0;
    }

    public function add_in_basket(int $id_product, int $quantity = 1): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($quantity))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $quantity = (int)htmlspecialchars((int)strip_tags((int)$quantity));

        if ($quantity < 1) {
            throw new Exception("Количество товаров не может быть меньше 1!");
        }

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $key => $bask) {
                if ((int)$bask["id_product"] === (int)$id_product) {
                    if ($this->id_user) {
                        $sql_query = "UPDATE basket SET quantity=quantity+$quantity WHERE id_user=$this->id_user AND id_product=$id_product;";
                        if (!$this->mysqli->query($sql_query)) {
                            throw new Exception("Неудалось выполнить запрос на добавление товара в корзину!");
                        }
                    }
                    $_SESSION["basket"][$key]["quantity"] += $quantity;
                    $message["success"][] = "Товар добавлен в корзину!";

                    return $message;
                }
            }

        $sql_query = "SELECT p.id_product, p.name, price, i.name as image_name FROM products p
                        LEFT JOIN images i ON p.id_img_main = i.id_image
                        WHERE p.id_product=$id_product;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку товара из базы данных!");
        }

        if ($row = $answer->fetch_assoc()) {
            if ($this->id_user) {
                $sql_query = "INSERT INTO basket(id_user, id_product, quantity) VALUES ($this->id_user, $id_product, $quantity);";
                if (!$this->mysqli->query($sql_query)) {
                    throw new Exception("Неудалось выполнить запрос на добавление товара в корзину!");
                }
            }

            $row["quantity"] = $quantity;
            $_SESSION["basket"][] = $row;
            $message["success"][] = "Товар добавлен в корзину!";
        } else {
            throw new Exception("Неудалось найти товар!");
        }

        return $message;
    }

    public function change_quantity(int $id_product, int $quantity): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($quantity))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $quantity = (int)htmlspecialchars((int)strip_tags((int)$quantity));

        if ($quantity < 1) {
            throw new Exception("Количество товаров не может быть меньше 1!");
        }

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $key => $bask) {
                if ((int)$bask["id_product"] === (int)$id_product) {
                    if ($this->id_user) {
                        $sql_query = "UPDATE basket SET quantity=$quantity WHERE id_user=$this->id_user AND id_product=$id_product;";
                        if (!$this->mysqli->query($sql_query)) {
                            throw new Exception("Неудалось выполнить запрос на изменение количества товара в корзине!");
                        }
                    }

                    $_SESSION["basket"][$key]["quantity"] = $quantity;
                    $message["success"][] = "Количество товара изменено!";

                    return $message;
                }
            }
        else {
            throw new Exception("Корзина пуста!");
        }

        throw new Exception("Неудалось найти товар в корзине!");
    }

    public function delete_in_basket(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $key => $bask) {
                if ((int)$bask["id_product"] === (int)$id_product) {
                    if ($this->id_user) {
                        $sql_query = "DELETE FROM basket WHERE id_user=$this->id_user AND id_product=$id_product;";
                        if (!$this->mysqli->query($sql_query)) {
                            throw new Exception("Неудалось удалить товар из корзины!");
                        }
                    }

                    unset($_SESSION["basket"][$key]);
                    $message["success"][] = "Товар удален из корзины!";

                    return $message;
                }
            }
        else {
            throw new Exception("Корзина пуста!");
        }

        throw new Exception("Неудалось найти товар в корзине!");
    }

    public function clear_basket(): array
    {
        $message = [];
        $message["success"] = [];

        if ($this->id_user) {
            $sql_query = "DELETE FROM basket WHERE id_user=$this->id_user;";
            if (!$this->mysqli->query($sql_query)) {
                throw new Exception("Неудалось очистить корзину!");
            }
        }

        unset($_SESSION["basket"]);
        $message["success"][] = "Корзина успешно очищена!";

        return $message;
    }

    public function basket_total(): int
    {
        $total = 0;

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $goods)
                $total += $goods["price"] * $goods["quantity"];

        return $total;
    }
}