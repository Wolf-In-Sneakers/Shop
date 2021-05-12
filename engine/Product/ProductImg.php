<?php

namespace Shop\Product;

use Exception;
use mysqli;
use Shop\Img\Thumbnail;

class ProductImg
{
    use Thumbnail;

    const FILE_EXTENSION = ["png", "jpg", "jpeg", "jpe", "jfif", "gif"];
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function upload_img(string $img_name, string $img_tmp_name, string $img_type, int $img_size): array
    {
        $message["success"] = [];

        if ((empty($img_name)) || (empty($img_tmp_name)) || (empty($img_type)) || (empty($img_size))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $img_name = $this->mysqli->real_escape_string((string)htmlspecialchars(strip_tags(basename((string)$img_name))));
        $img_type = $this->mysqli->real_escape_string((string)htmlspecialchars(strip_tags((string)$img_type)));
        $img_ext = end(explode(".", $img_name));

        if ((in_array($img_ext, self::FILE_EXTENSION)) and (explode("/", $img_type)[0] == "image") and ($img_size <= MAX_SIZE)) {
            $img_name = hash_file('md5', $img_tmp_name) . "." . $img_ext;
            $img_path = UPLOAD_DIR . $img_name;
            $img_path_small = UPLOAD_DIR_SMALL . $img_name;

            if (move_uploaded_file($img_tmp_name, $img_path)) {
                $message["success"][] = "Изображение загруженно на сервер!";
            } else {
                throw new Exception("Не удалось загрузить изображение на сервер!");
            }

            if ($this->create_thumbnail($img_path, $img_path_small, 200, 200)) {
                $message["success"][] = "Создана уменьшенная копия изображения!";
            } else {
                throw new Exception("Не удалось создать уменьшенную копию изображения!");
            }

            $answer = $this->upload_img_in_db($img_name);
            array_push($message["success"], ...$answer["success"]);
            $message["id_image"] = $answer['id_image'];
        } else
            throw new Exception("Файл не соответствует формату изображения или размер файла больше 2Mb!");

        return $message;
    }

    private function upload_img_in_db(string $img_name): array
    {
        $message["success"] = [];

        if (empty($img_name)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $sql_query = "SELECT id_image FROM images WHERE name='$img_name' LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
        }
        if ($row = $answer->fetch_assoc()) {
            $message["id_image"] = $row['id_image'];
            $message["success"][] = "Изображение уже существует в базе данных!";
            return $message;
        }

        $sql_query = "INSERT INTO images(name) VALUES ('$img_name');";
        if (!$this->mysqli->query($sql_query)) {
            throw new Exception("Неудалось выполнить запрос на добавление изображения в базу данных!");
        }
        if ($message["id_image"] = $this->mysqli->insert_id)
            $message["success"][] = "Изображение добавленно в базу данных!";
        else
            throw new Exception("Не удалось загрузить изображение в базу данных!");

        return $message;
    }

    public function create_bond_product_img(int $id_product, int $id_image): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($id_image))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT * FROM images_products WHERE id_product=$id_product AND id_image=$id_image LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
        }

        if ($answer->fetch_assoc())
            $message["success"][] = "Связь между изображением и товаром уже существует!";
        else {
            $sql_query = "INSERT INTO images_products(id_product, id_image) VALUES ($id_product, $id_image);";
            if ($this->mysqli->query($sql_query))
                $message["success"][] = "Созданна связь между изображением и товаром!";
            else
                throw new Exception("Не удаллось создать связь между изображением и товаром!");
        }

        return $message;
    }

    public function read_img_all(int $id_product): array
    {
        $message["success"] = [];
        $message["img"] = [];

        if (empty($id_product)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT i.id_image, i.name FROM images_products ip
                        INNER JOIN images i ON ip.id_image = i.id_image 
                        WHERE ip.id_product = $id_product;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
        }

        while ($row = $answer->fetch_assoc())
            $message["img"][] = $row;

        if (!empty($message["img"]))
            $message["success"][] = "Изображения из базы данных получены!";
        else
            $message["success"][] = "В базе данных нет изображений!";

        return $message;
    }

    public function delete_img(int $id_product, int $id_image): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($id_image))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT id_image FROM images_products WHERE id_product=$id_product AND id_image=$id_image LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
        }

        if ($answer->fetch_assoc()) {
            $sql_query = "SELECT name FROM images WHERE id_image=$id_image LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
            }
            if ($row = $answer->fetch_assoc()) {
                $img_delete_name = UPLOAD_DIR . $row['name'];
                $img_delete_name_small = UPLOAD_DIR_SMALL . $row['name'];
            } else {
                throw new Exception("Неудалось найти изображение в базе данных!");
            }

            $sql_query = "SELECT id_img_main FROM products WHERE id_product=$id_product AND id_img_main=$id_image LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
            }
            $delete_img_main = false;
            if ($answer->fetch_assoc())
                $delete_img_main = true;

            $sql_query = "SELECT ip.id_product FROM images_products ip WHERE ip.id_image=$id_image AND ip.id_product<>$id_product UNION
                            SELECT iu.id_user FROM images_users iu where iu.id_image=$id_image;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
            }
            if ($answer->fetch_assoc()) {
                $sql_query = "DELETE FROM images_products WHERE id_product=$id_product AND id_image=$id_image;";
                if ($this->mysqli->query($sql_query))
                    $message["success"][] = "Связь между изображением и товаром удалена из базы данных!";
                else {
                    throw new Exception("Не удалось удалить связь между изображением и товаром из базы данных!");
                }
            } else {
                $sql_query = "DELETE FROM images WHERE id_image=$id_image";
                if ($this->mysqli->query($sql_query)) {
                    $message["success"][] = "Изображение удалено из базы данных!";

                    unlink($img_delete_name);
                    unlink($img_delete_name_small);
                    $message["success"][] = "Изображение удалено с сервера!";
                } else {
                    throw new Exception("Не удалось удалить изображение из базы данных!");
                }
            }

            if ($delete_img_main) {
                $sql_query = "SELECT * FROM images_products WHERE id_product=$id_product ORDER BY created LIMIT 1;";
                if (!($answer = $this->mysqli->query($sql_query))) {
                    throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
                }
                if ($row = $answer->fetch_assoc()) {
                    $answer = $this->set_main_img($id_product, $row['id_image']);
                    array_push($message["success"], ...$answer["success"]);
                } else {
                    $sql_query = "UPDATE products SET id_img_main=null WHERE id_product=$id_product;";
                    if (!($this->mysqli->query($sql_query))) {
                        throw new Exception("Не удалось изменить главное изображение!");
                    }
                    $message["success"][] = "Главное изображение товара пусто!";
                }
            }
        } else
            throw new Exception("Неверно указан товар или изображение!");

        return $message;
    }

    public function set_main_img(int $id_product, int $id_img_main): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($id_img_main))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $id_img_main = (int)htmlspecialchars((int)strip_tags((int)$id_img_main));

        $sql_query = "SELECT id_image FROM images_products WHERE id_product=$id_product AND id_image=$id_img_main LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            throw new Exception("Неудалось выполнить запрос на выбоку изображений из базы данных!");
        }

        if ($answer->fetch_assoc()) {
            $sql_query = "UPDATE products SET id_img_main=$id_img_main WHERE id_product=$id_product;";
            if ($this->mysqli->query($sql_query))
                $message["success"][] = "Главное изображение изменено!";
            else
                throw new Exception("Не удалось изменить главное изображение!");
        } else
            throw new Exception("Неверно указан товар или изображение!");

        return $message;
    }
}