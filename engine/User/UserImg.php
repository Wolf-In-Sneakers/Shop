<?php

namespace Shop\User;

use mysqli;

class UserImg
{
    const FILE_EXTENSION = ["png", "jpg", "jpeg", "jpe", "jfif", "gif"];
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function upload_img(string $img_name, string $img_tmp_name, string $img_type, int $img_size): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($img_name)) || (empty($img_tmp_name)) || (empty($img_type)) || (empty($img_size))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
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
                $message["error"] = "Не удалось загрузить изображение на сервер!";
                return $message;
            }

            if ($this->create_thumbnail($img_path, $img_path_small, 200, 200)) {
                $message["success"][] = "Создана уменьшенная копия изображения!";
            } else {
                $message["error"] = "Не удалось создать уменьшенную копию изображения!";
                return $message;
            }

            $answer = $this->upload_img_in_db($img_name);
            array_push($message["success"], ...$answer["success"]);
            if (!empty($answer['error'])) {
                $message['error'] = $answer['error'];
                return $message;
            }
            $message["id_image"] = $answer['id_image'];
        } else
            $message["error"] = "Файл не соответствует формату изображения или размер файла больше 2Mb!";

        return $message;
    }

    private function create_thumbnail(string $path, string $save, int $width, int $height) // Данная функция была прикрепленна к уроку в GeekBrains
    {
        $info = getimagesize($path); //получаем размеры картинки и ее тип
        $size = array($info[0], $info[1]); //закидываем размеры в массив

        //В зависимости от расширения картинки вызываем соответствующую функцию
        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($path); //создаём новое изображение из файла
        } else if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($path);
        } else if ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($path);
        } else {
            return false;
        }

        $thumb = imagecreatetruecolor($width, $height); //возвращает идентификатор изображения, представляющий черное изображение заданного размера
        $src_aspect = $size[0] / $size[1]; //отношение ширины к высоте исходника
        $thumb_aspect = $width / $height; //отношение ширины к высоте аватарки

        if ($src_aspect < $thumb_aspect) {
            //узкий вариант (фиксированная ширина)
            $scale = $width / $size[0];
            $new_size = array($width, $width / $src_aspect);
            $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2); //Ищем расстояние по высоте от края картинки до начала картины после обрезки
        } else if ($src_aspect > $thumb_aspect) {
            //широкий вариант (фиксированная высота)
            $scale = $height / $size[1];
            $new_size = array($height * $src_aspect, $height);
            $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0); //Ищем расстояние по ширине от края картинки до начала картины после обрезки
        } else {
            //другое
            $new_size = array($width, $height);
            $src_pos = array(0, 0);
        }

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);
        //Копирование и изменение размера изображения с ресемплированием

        if ($save === false) {
            return imagepng($thumb); //Выводит JPEG/PNG/GIF изображение
        } else {
            return imagepng($thumb, $save); //Сохраняет JPEG/PNG/GIF изображение
        }
    }

    function upload_img_in_db(string $img_name): array
    {
        $message = [];
        $message["success"] = [];

        if (empty($img_name)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $sql_query = "SELECT id_image FROM images WHERE name='$img_name' LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }
        if ($row = $answer->fetch_assoc()) {
            $message["id_image"] = $row['id_image'];
            $message["success"][] = "Изображение уже существует в базе данных!";
            return $message;
        }

        $sql_query = "INSERT INTO images(name) VALUES ('$img_name');";
        if (!$this->mysqli->query($sql_query)) {
            $message["error"] = "Неудалось выполнить запрос на добавление изображения в базу данных!";
            return $message;
        }
        if ($message["id_image"] = $this->mysqli->insert_id)
            $message["success"][] = "Изображение добавленно в базу данных!";
        else
            $message["error"] = "Не удалось загрузить изображение в базу данных!";

        return $message;
    }

    public function create_bond_user_img(int $id_user, int $id_image): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($id_user)) || (empty($id_image))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$id_user));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT * FROM images_users WHERE id_user=$id_user AND id_image=$id_image LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }

        if ($answer->fetch_assoc())
            $message["success"][] = "Связь между изображением и пользователем уже существует!";
        else {
            $sql_query = "INSERT INTO images_users(id_user, id_image) VALUES ($id_user, $id_image);";
            if ($this->mysqli->query($sql_query))
                $message["success"][] = "Созданна связь между изображением и пользователем!";
            else
                $message["error"] = "Не удаллось создать связь между изображением и пользователем!";
        }

        return $message;
    }

    public function read_img_all(int $id_user): array
    {
        $message = [];
        $message["success"] = [];
        $message["img"] = [];

        if (empty($id_user)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$id_user));

        $sql_query = "SELECT i.id_image, name FROM images_users iu
                        INNER JOIN images i ON iu.id_image = i.id_image 
                        WHERE iu.id_user = $id_user;";

        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }

        while ($row = $answer->fetch_assoc())
            $message["img"][] = $row;

        if (!empty($message["img"]))
            $message["success"][] = "Изображения из базы данных получены!";
        else
            $message["success"][] = "В базе данных нет изображений!";

        return $message;
    }

    public function delete_img(int $id_user, int $id_image): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($id_user)) || (empty($id_image))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$id_user));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT id_image FROM images_users WHERE id_user=$id_user AND id_image=$id_image LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }

        if ($answer->fetch_assoc()) {
            $sql_query = "SELECT name FROM images WHERE id_image=$id_image LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
                return $message;
            }
            if ($row = $answer->fetch_assoc()) {
                $img_delete_name = UPLOAD_DIR . $row['name'];
                $img_delete_name_small = UPLOAD_DIR_SMALL . $row['name'];
            } else {
                $message["error"] = "Неудалось найти изображение в базе данных!";
                return $message;
            }

            $sql_query = "SELECT id_img_main FROM users WHERE id_user=$id_user AND id_img_main=$id_image LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
                return $message;
            }
            $delete_img_main = false;
            if ($row = $answer->fetch_assoc())
                $delete_img_main = true;

            $sql_query = "SELECT iu.id_user FROM images_users iu where iu.id_image=$id_image and iu.id_user<>$id_user UNION
                            SELECT ip.id_product FROM images_products ip where ip.id_image=$id_image;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
                return $message;
            }
            if ($answer->fetch_assoc()) {
                $sql_query = "DELETE FROM images_users WHERE id_user=$id_user AND id_image=$id_image;";
                if ($this->mysqli->query($sql_query))
                    $message["success"][] = "Связь между изображением и товаром удалена из базы данных!";
                else {
                    $message["error"] = "Не удалось удалить связь между изображением и пользователем из базы данных!";
                    return $message;
                }
            } else {
                $sql_query = "DELETE FROM images WHERE id_image=$id_image";
                if ($this->mysqli->query($sql_query)) {
                    $message["success"][] = "Изображение удалено из базы данных!";

                    unlink($img_delete_name);
                    unlink($img_delete_name_small);
                    $message["success"][] = "Изображение удалено с сервера!";
                } else {
                    $message["error"] = "Не удалось удалить изображение из базы данных!";
                    return $message;
                }
            }

            if ($delete_img_main) {
                $sql_query = "SELECT * FROM images_users WHERE id_user=$id_user ORDER BY created LIMIT 1;";
                if (!($answer = $this->mysqli->query($sql_query))) {
                    $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
                    return $message;
                }
                if ($row = $answer->fetch_assoc()) {
                    $answer = $this->set_main_img($id_user, $row['id_image']);
                    array_push($message["success"], ...$answer["success"]);
                    if (!empty($answer['error']))
                        $message['error'] = $answer['error'];
                } else
                    $message["success"][] = "Главное изображение пользователя пусто!";
            }
        } else
            $message["error"] = "Неверно указан пользователь или изображение!";

        return $message;
    }

    public function set_main_img(int $id_user, int $id_img_main): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($id_user)) || (empty($id_img_main))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        $id_user = (int)htmlspecialchars((int)strip_tags((int)$id_user));
        $id_img_main = (int)htmlspecialchars((int)strip_tags((int)$id_img_main));

        $sql_query = "SELECT id_image FROM images_users WHERE id_user=$id_user AND id_image=$id_img_main LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку изображений из базы данных!";
            return $message;
        }

        if ($answer->fetch_assoc()) {
            $sql_query = "UPDATE users SET id_img_main=$id_img_main WHERE id_user=$id_user;";
            if ($this->mysqli->query($sql_query))
                $message["success"][] = "Главное изображение пользователя изменено!";
            else
                $message["error"] = "Не удалось изменить главное изображение!";
        } else
            $message["error"] = "Неверно указан пользователь или изображение!";

        return $message;
    }
}