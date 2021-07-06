<?php

namespace Shop\models\Basket;

use Exception;
use Shop\core\Model;

class Basket extends Model
{
    private int $id_user;

    public function __construct()
    {
        parent::__construct();

        $this->id_user = (!empty($_SESSION["user"])) ? (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]["id_user"])) : 0;
    }

    public function addInBasket(int $id_product, int $quantity = 1): array
    {
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
                    if (!empty($this->id_user)) {
                        $sql_query = "UPDATE basket SET quantity=quantity+? WHERE id_user=? AND id_product= ?;";
                        $this->db->query($sql_query, [$quantity, $this->id_user, $id_product]);
                    }
                    $_SESSION["basket"][$key]["quantity"] += $quantity;
                    $message = $_SESSION["basket"][$key];

                    return $message;
                }
            }

        $sql_query = "SELECT p.id_product, p.name, price, i.name as image_name FROM products p
                        LEFT JOIN images i ON p.id_img_main = i.id_image
                        WHERE p.id_product=? LIMIT 1;";
        $answer = $this->db->fetchRow($sql_query, [$id_product]);

        if (!empty($answer)) {
            if (!empty($this->id_user)) {
                $sql_query = "INSERT INTO basket(id_user, id_product, quantity) VALUES (?, ?, ?);";
                $this->db->query($sql_query, [$this->id_user, $id_product, $quantity]);
            }

            if (!empty($answer["image_name"])) {
                $answer["image_name"] = IMG_DIR_SMALL . $answer["image_name"];
            } else {
                $answer["image_name"] = IMG_NOT_FOUND;
            }

            $answer["quantity"] = $quantity;
            $_SESSION["basket"][] = $answer;
            $message = $answer;
        } else {
            throw new Exception("Неудалось найти товар!");
        }

        return $message;
    }

    public function changeQuantity(int $id_product, int $quantity): array
    {
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
                    if (!empty($this->id_user)) {
                        $sql_query = "UPDATE basket SET quantity=? WHERE id_user=? AND id_product=?;";
                        $this->db->query($sql_query, [$quantity, $this->id_user, $id_product]);
                    }

                    $_SESSION["basket"][$key]["quantity"] = $quantity;
                    $message = $_SESSION["basket"][$key];
                    return $message;
                }
            }
        else {
            throw new Exception("Корзина пуста!");
        }

        throw new Exception("Неудалось найти товар в корзине!");
    }

    public function deleteInBasket(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $key => $bask) {
                if ((int)$bask["id_product"] === (int)$id_product) {
                    if (!empty($this->id_user)) {
                        $sql_query = "DELETE FROM basket WHERE id_user=? AND id_product=?;";
                        $this->db->query($sql_query, [$this->id_user, $id_product]);
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

    public function clearBasket(): array
    {
        $message["success"] = [];

        if (!empty($this->id_user)) {
            $sql_query = "DELETE FROM basket WHERE id_user=?;";
            $this->db->query($sql_query, $this->id_user);
        }

        unset($_SESSION["basket"]);
        $message["success"][] = "Корзина успешно очищена!";

        return $message;
    }

    public function basketTotal(): int
    {
        $total = 0;

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $goods)
                $total += $goods["price"] * $goods["quantity"];

        return $total;
    }
}