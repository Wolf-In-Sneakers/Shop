<?php

namespace Shop\models\Product;

use Exception;
use Shop\core\Model;

class Product extends Model
{
    private ProductImg $product_img;
    private array $table_names = ["categories", "genders", "brands"];

    public function __construct()
    {
        parent::__construct();

        $this->product_img = new ProductImg();
    }

    public function addGoods(string $name, int $id_category, int $id_brand, int $price, int $id_gender = 0, array $imgs = []): array
    {
        $message["success"] = [];

        if ((empty($name)) || (empty($id_category)) || (empty($id_brand)) || (empty($price))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        if ($price < 1) {
            throw new Exception("Цена не может быть меньше 1 рубля!");
        }

        $name = htmlspecialchars((string)strip_tags(trim((string)$name)));
        $id_category = (int)htmlspecialchars((int)strip_tags((int)$id_category));
        $id_brand = (int)htmlspecialchars((int)strip_tags((int)$id_brand));
        $price = (int)htmlspecialchars((int)strip_tags((int)$price));
        $id_gender = (!empty($id_gender)) ? (int)htmlspecialchars((int)strip_tags((int)$id_gender)) : null;

        foreach ($imgs["size"] as $key => $img_size) {
            if (!empty($img_size)) {
                $answer = $this->product_img->uploadImg($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                $id_images[] = $answer['id_image'];
            }
        }

        $sql_query = "INSERT INTO products(name, id_category, id_gender, id_brand, id_img_main, price) VALUES (?, ?, ?, ?, ?, ?);";
        $this->db->query($sql_query, [$name, $id_category, $id_gender, $id_brand, ((!empty($id_images)) ? $id_images[0] : null), $price]);

        if ($id_product = $this->db->getConnection()->lastInsertId()) {
            $message["id_product"] = $id_product;
            if (!empty($id_images)) {
                foreach ($id_images as $id_image) {
                    $answer = $this->product_img->createBondImg($id_product, $id_image);
                    array_push($message["success"], ...$answer["success"]);
                }
            }
            $message["success"][] = "Товар успешно добавлен!";
        } else
            throw new Exception("Неудалось добавить товар!");

        return $message;
    }

    public function readGoodsAllCategory($id_category): array
    {
        $message["success"] = [];
        $message["goods"] = [];

        if (empty($id_category)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $sql_query = "SELECT products.id_product, products.name, price, images.name as image_name, liked, viewed FROM products 
                        LEFT JOIN images ON products.id_img_main = images.id_image
                        WHERE id_category=? 
                        ORDER BY products.viewed DESC, products.liked DESC;";
        $message["goods"] = $this->db->fetchAll($sql_query, [$id_category]);

        foreach ($message["goods"] as $key => $item) {
            $message["goods"][$key]["image_name"] = (empty($item["image_name"])) ? IMG_NOT_FOUND : IMG_DIR . $item["image_name"];
        }

        $message["success"][] = "Товары успешно полученны из базы данных!";

        return $message;
    }

    public function readGoodsAllFeatured(): array
    {
        $message["success"] = [];
        $message["goods"] = [];

        $sql_query = "SELECT products.id_product, products.name, price, images.name as image_name, liked, viewed FROM products 
                        LEFT JOIN images ON products.id_img_main = images.id_image
                        ORDER BY products.viewed DESC, products.liked DESC LIMIT 20;";
        $message["goods"] = $this->db->fetchAll($sql_query);

        foreach ($message["goods"] as $key => $item) {
            $message["goods"][$key]["image_name"] = (empty($item["image_name"])) ? IMG_NOT_FOUND : IMG_DIR . $item["image_name"];
        }

        $message["success"][] = "Товары успешно полученны из базы данных!";

        return $message;
    }

    public function readGoodsOne(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            $message["goods"]["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT p.id_product, p.name, c.name as category, g.name as gender, b.name as brand, price, p.viewed, p.liked FROM products p 
                        INNER JOIN categories c on p.id_category = c.id_category
                        INNER JOIN brands b ON p.id_brand = b.id_brand
                        LEFT JOIN genders g ON p.id_gender = g.id_gender
                        WHERE p.id_product=?
                        LIMIT 1;";
        $message["goods"] = $this->db->fetchRow($sql_query, [$id_product]);

        if (!empty($message["goods"])) {
            $message["success"][] = "Товар успешно получен из таблицы!";
        } else {
            $message["goods"]["error"] = "Неудалось найти товар в базе данных!";
            return $message;
        }

        try {
            $answer = $this->product_img->readImgAll($id_product);
            array_push($message["success"], ...$answer["success"]);
            $message["goods"]["img"] = $answer['img'];

            foreach ($message["goods"]["img"] as $key => $item) {
                $message["goods"]["img"][$key]["name"] = IMG_DIR . $item["name"];
            }

            $answer = $this->updateView($id_product);
            array_push($message["success"], ...$answer["success"]);
        } catch (Exception $e) {
            $message["goods"]["error"] = $e->getMessage();
        }

        return $message;
    }

    public function updateGoods(int $id_product, string $name = "", int $id_category = 0, int $id_gender = 0, int $id_brand = 0, int $price = 0, array $imgs = []): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT id_product, name, id_category, id_gender, id_brand, price FROM products WHERE id_product=? LIMIT 1;";
        $product = $this->db->fetchRow($sql_query, [$id_product]);

        $name = ((!empty($name)) and ((string)$name !== (string)$product['name'])) ? htmlspecialchars((string)strip_tags((string)$name)) : $product['name'];
        $id_category = ((!empty($id_category)) and ((int)$id_category !== (int)$product['id_category'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_category)) : $product['id_category'];
        $id_brand = ((!empty($id_brand)) and ((int)$id_brand !== (int)$product['id_brand'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_brand)) : $product['id_brand'];
        $price = ((!empty($price)) and ((int)$price !== (int)$product['price'])) ? (int)htmlspecialchars((int)strip_tags((int)$price)) : $product['price'];
        $id_gender = ((!empty($id_gender)) and ((int)$id_gender !== (int)$product['id_gender'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_gender)) : $product['id_gender'];
        if ($id_gender === -1)
            $id_gender = null;

        if ((!empty($price)) && ($price < 1)) {
            throw new Exception("Цена не может быть меньше 1 рубля!");
        }

        $sql_query = "UPDATE products SET name=?, id_category=?, id_gender=?, id_brand=?, price=? WHERE id_product=?;";
        $this->db->query($sql_query, [$name, $id_category, $id_gender, $id_brand, $price, $id_product]);


        foreach ($imgs["size"] as $key => $img_size) {
            if (!empty($img_size)) {
                $answer = $this->product_img->uploadImg($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                $id_image = $answer['id_image'];

                if (empty($first_image))
                    $first_image = $id_image;

                $answer = $this->product_img->createBondImg($id_product, $id_image);
                array_push($message["success"], ...$answer["success"]);
            }
        }

        if (!empty($first_image)) {
            $sql_query = "SELECT id_img_main FROM products WHERE id_product=? LIMIT 1;";
            $answer = $this->db->fetchRow($sql_query, [$id_product]);

            if (!empty($answer))
                if (empty($answer['id_img_main'])) {
                    $answer = $this->product_img->setMainImg($id_product, $first_image);
                    array_push($message["success"], ...$answer["success"]);
                }
        }

        $message["success"][] = "Товар успешно изменен!";
        return $message;
    }

    public function search(string $query)
    {
        $message["success"] = [];

        if (empty($query) or (strlen($query) < 3)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $query = htmlspecialchars((string)strip_tags((string)$query));
        $query = "%$query%";

        $sql_query = "SELECT p.id_product, p.name, price, i.name as image_name, liked, viewed FROM products p
                        LEFT JOIN images i ON p.id_img_main = i.id_image
                        INNER JOIN sections_categories sc ON sc.id=p.id_category
                        INNER JOIN categories c ON c.id_category=sc.id_category
                        INNER JOIN brands b ON b.id_brand=p.id_brand
                        LEFT JOIN genders g ON g.id_gender=p.id_gender
                        WHERE p.name LIKE ? OR c.name LIKE ? OR b.name LIKE ? OR g.name LIKE ?
                        ORDER BY p.viewed DESC, p.liked DESC;";
        $message["goods"] = $this->db->fetchAll($sql_query, [$query, $query, $query, $query]);

        foreach ($message["goods"] as $key => $item) {
            $message["goods"][$key]["image_name"] = (empty($item["image_name"])) ? IMG_NOT_FOUND : IMG_DIR . $item["image_name"];
        }

        return $message;
    }

    public function goodsCharacter(): array
    {
        $message["success"] = [];
        $message["characters"] = [];

        foreach ($this->table_names as $table_name) {
            $sql_query = "SELECT * FROM $table_name;";
            $message["characters"][$table_name] = $this->db->fetchAll($sql_query);
        }

        $message["success"][] = "Таблицы успешно получены!";
        return $message;
    }

    public function updateView(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "UPDATE products SET viewed=viewed + 1 WHERE id_product=?;";
        $this->db->query($sql_query, [$id_product]);

        $message["success"][] = "Количество просмотров товара увеличелось!";

        return $message;
    }

    public function like(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "UPDATE products SET liked=liked + 1 WHERE id_product=?;";
        $this->db->query($sql_query, [$id_product]);

        $message["success"] = "Like поставлен!";

        return $message;
    }

    public function deleteGoods(int $id_product): array
    {
        $message["success"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT id_image FROM images_products WHERE id_product=$id_product;";
        $answer = $this->db->fetchAll($sql_query, [$id_product]);

        foreach ($answer as $row) {
            $delete_img = $this->product_img->deleteImg($id_product, $row["id_image"]);
            array_push($message["success"], ...$delete_img["success"]);
        }

        $sql_query = "DELETE FROM products WHERE id_product=?;";
        $this->db->query($sql_query, [$id_product]);

        if (!empty($_SESSION["basket"]))
            foreach ($_SESSION["basket"] as $key => $bask)
                if ((int)$bask["id_product"] === (int)$id_product)
                    unset($_SESSION["basket"][$key]);

        $message["success"][] = "Товар удален!";


        return $message;
    }
}