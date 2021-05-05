<?php

namespace Shop\Product;

use mysqli;

class Product
{
    private mysqli $mysqli;
    private ProductImg $product_img;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->product_img = new ProductImg($this->mysqli);
    }

    public function add_goods(string $name, int $id_type, int $id_brand, int $price, int $id_gender = 0, array $imgs = []): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($name)) || (empty($id_type)) || (empty($id_brand)) || (empty($price))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        if ($price < 1) {
            $message["error"] = "Цена не может быть меньше 1 рубля!";
            return $message;
        }

        $name = $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags(trim((string)$name))));
        $id_type = (int)htmlspecialchars((int)strip_tags((int)$id_type));
        $id_brand = (int)htmlspecialchars((int)strip_tags((int)$id_brand));
        $price = (int)htmlspecialchars((int)strip_tags((int)$price));
        $id_gender = (!empty($id_gender)) ? (int)htmlspecialchars((int)strip_tags((int)$id_gender)) : null;


        foreach ($imgs["size"] as $key => $img_size) {
            if ((!empty($img_size)) && (is_uploaded_file($imgs['tmp_name'][$key]))) {
                $answer = $this->product_img->upload_img($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                if (!empty($answer['error'])) {
                    $message['error'] = $answer['error'];
                    return $message;
                }
                $id_images[] = $answer['id_image'];
            }
        }

        $sql_query = "INSERT INTO products(name, id_type," . ((!empty($id_gender)) ? " id_gender, " : " ") . "id_brand," . ((!empty($id_images)) ? " id_img_main, " : " ") . "price) 
                        VALUES ('$name', $id_type," . ((!empty($id_gender)) ? " $id_gender, " : " ") . "$id_brand," . ((!empty($id_images)) ? " $id_images[0], " : " ") . "$price);";
        if (!$this->mysqli->query($sql_query)) {
            $message["error"] = "В запрос на добавление товара произошла ошибка!";
            return $message;
        }

        if ($id_product = $this->mysqli->insert_id) {
            $message["id_product"] = $id_product;
            if (!empty($id_images)) {
                foreach ($id_images as $id_image) {
                    $answer = $this->product_img->create_bond_product_img($id_product, $id_image);
                    array_push($message["success"], ...$answer["success"]);
                    if (!empty($answer['error'])) {
                        $message['error'] = $answer['error'];
                        return $message;
                    }
                }
            }
            $message["success"][] = "Товар успешно добавлен!";
        } else
            $message["error"] = "Неудалось добавить товар!";

        return $message;
    }

    public function read_goods_all(): array
    {
        $message = [];
        $message["success"] = [];
        $message["goods"] = [];

        $sql_query = "SELECT products.id_product, products.name, price, images.name as image_name, liked, viewed FROM products 
                        LEFT JOIN images ON products.id_img_main = images.id_image
                        ORDER BY products.viewed DESC, products.liked DESC;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку товаров из базы данных!";
            return $message;
        }

        while ($row = $answer->fetch_assoc())
            $message["goods"][] = $row;

        if (!empty($message["goods"]))
            $message["success"][] = "Товары успешно полученны из базы данных!";
        else
            $message["error"] = "Неудалось получить товары из базы данных!";

        return $message;
    }

    public function read_goods_one(int $id_product): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT products.id_product, products.name, types_products.name as type, genders.name as gender, brands.name as brand, price, products.viewed, products.liked FROM products 
                        INNER JOIN types_products ON products.id_type = types_products.id_type_product
                        INNER JOIN brands ON products.id_brand = brands.id_brand
                        LEFT JOIN genders ON products.id_gender = genders.id_gender
                        WHERE products.id_product=$id_product;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку товара из базы данных!";
            return $message;
        }

        if ($row = $answer->fetch_assoc()) {
            $message["goods"] = $row;
            $message["success"][] = "Товар успешно получен из таблицы!";
        } else {
            $message["error"] = "Неудалось найти товар в базе данных!";
            return $message;
        }

        $answer = $this->product_img->read_img_all($id_product);
        array_push($message["success"], ...$answer["success"]);
        $message["img"] = $answer['img'];
        if (!empty($answer['error'])) {
            $message['error'] = $answer['error'];
            return $message;
        }

        $answer = $this->update_view($id_product);
        array_push($message["success"], ...$answer["success"]);
        if (!empty($answer['error'])) {
            $message['error'] = $answer['error'];
        }

        return $message;
    }

    public function update_view(int $id_product): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "UPDATE products SET viewed=viewed + 1 WHERE id_product=$id_product;";
        if ($this->mysqli->query($sql_query))
            $message["success"][] = "Количество просмотров товара увеличелось!";
        else
            $message["error"] = "Неудалось увеличить количество просмотров товара!";

        return $message;
    }

    public function update_goods(int $id_product, string $name = "", int $id_type = 0, int $id_gender = 0, int $id_brand = 0, int $price = 0, array $imgs = []): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT id_product, name, id_type, id_gender, id_brand, price FROM products WHERE id_product=$id_product LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку товара из базы данных!";
            return $message;
        }
        if ($row = $answer->fetch_assoc()) {
            $product = $row;
        } else {
            $message["error"] = "Неудалось найти товар в базе данных!";
            return $message;
        }

        $name = ((!empty($name)) and ((string)$name !== (string)$product['name'])) ? $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)$name))) : null;
        $id_type = ((!empty($id_type)) and ((int)$id_type !== (int)$product['id_type'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_type)) : null;
        $id_brand = ((!empty($id_brand)) and ((int)$id_brand !== (int)$product['id_brand'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_brand)) : null;
        $price = ((!empty($price)) and ((int)$price !== (int)$product['price'])) ? (int)htmlspecialchars((int)strip_tags((int)$price)) : null;
        $id_gender = ((!empty($id_gender)) and ((int)$id_gender !== (int)$product['id_gender'])) ? (int)htmlspecialchars((int)strip_tags((int)$id_gender)) : null;
        if ($id_gender === -1)
            $id_gender = "null";

        if ((!empty($price)) && ($price < 1)) {
            $message["error"] = "Цена не может быть меньше 1 рубля!";
            return $message;
        }

        $sql_query = "UPDATE products SET ";
        $change_exists = false;
        if (isset($name)) {
            $sql_query .= "name='$name'";
            $change_exists = true;
        }
        if (isset($id_type)) {
            $sql_query .= ($change_exists) ? ", id_type=$id_type" : "id_type=$id_type";
            $change_exists = true;
        }
        if (isset($id_gender)) {
            $sql_query .= ($change_exists) ? ", id_gender=$id_gender" : "id_gender=$id_gender";
            $change_exists = true;
        }
        if (isset($id_brand)) {
            $sql_query .= ($change_exists) ? ", id_brand=$id_brand" : "id_brand=$id_brand";
            $change_exists = true;
        }
        if (isset($price)) {
            $sql_query .= ($change_exists) ? ", price=$price" : "price=$price";
            $change_exists = true;
        }
        if ($change_exists) {
            $sql_query .= " WHERE id_product=$id_product;";
            if (!$this->mysqli->query($sql_query)) {
                $message["error"] = "В запрос на изменение товара произошла ошибка!";
                return $message;
            }
        }

        foreach ($imgs["size"] as $key => $img_size) {
            if ((!empty($img_size)) && (is_uploaded_file($imgs['tmp_name'][$key]))) {
                $answer = $this->product_img->upload_img($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                if (!empty($answer['error'])) {
                    $message['error'] = $answer['error'];
                    return $message;
                }
                $id_image = $answer['id_image'];

                if (empty($first_image))
                    $first_image = $id_image;

                $answer = $this->product_img->create_bond_product_img($id_product, $id_image);
                array_push($message["success"], ...$answer["success"]);
                if (!empty($answer['error'])) {
                    $message['error'] = $answer['error'];
                    return $message;
                }

            }
        }

        if (!empty($first_image)) {
            $sql_query = "SELECT id_img_main FROM products WHERE id_product=$id_product LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                $message["error"] = "Неудалось выполнить запрос на выбоку товара из базы данных!";
                return $message;
            }
            if ($row = $answer->fetch_assoc())
                if (empty($row['id_img_main'])) {
                    $answer = $this->product_img->set_main_img($id_product, $first_image);
                    array_push($message["success"], ...$answer["success"]);
                    if (!empty($answer['error'])) {
                        $message['error'] = $answer['error'];
                        return $message;
                    }
                }
        }

        $message["success"][] = "Товар успешно изменен!";
        return $message;
    }

    public function like(int $id_product): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "UPDATE products SET liked=liked + 1 WHERE id_product=(SELECT id_product FROM products WHERE id_product=$id_product LIMIT 1);";
        if ($this->mysqli->query($sql_query))
            $message["success"] = "Like поставлен!";
        else
            $message["error"] = "Неудалось найти товар!";

        return $message;
    }

    public function delete_goods(int $id_product): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT id_image FROM images_products WHERE id_product=$id_product;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }

        while ($row = $answer->fetch_assoc()) {
            $delete_img = $this->product_img->delete_img($id_product, $row["id_image"]);
            array_push($message["success"], ...$delete_img["success"]);
            if (!empty($delete_img["error"])) {
                $message['error'] = $delete_img["error"];
                return $message;
            }
        }

        $sql_query = "DELETE FROM products WHERE id_product=$id_product;";
        if ($this->mysqli->query($sql_query)) {
            if (!empty($_SESSION["basket"]))
                foreach ($_SESSION["basket"] as $key => $bask)
                    if ((int)$bask["id_product"] === (int)$id_product)
                        unset($_SESSION["basket"][$key]);

            $message["success"][] = "Товар удален!";
        } else
            $message["error"] = "Неудалось удалить товар!";

        return $message;
    }
}