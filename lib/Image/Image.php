<?php


namespace Shop\lib\Image;


use Exception;
use Shop\core\Model;

abstract class Image extends Model
{
    protected array $table_name;
    protected string $pole_name;


    public function uploadImg(string $img_name, string $img_tmp_name, string $img_type, int $img_size): array
    {
        $message["success"] = [];

        if ((empty($img_name)) || (empty($img_tmp_name)) || (empty($img_type)) || (empty($img_size))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        if (!is_uploaded_file($img_tmp_name)) {
            throw new Exception("Файл загружен не через HTTP POST");
        }

        $img_name = htmlspecialchars(strip_tags(basename((string)$img_name)));
        $img_type = htmlspecialchars(strip_tags((string)$img_type));
        $img_ext = end(explode(".", $img_name));

        if ((in_array($img_ext, FILE_EXTENSION)) and (explode("/", $img_type)[0] == "image") and ($img_size <= MAX_SIZE)) {
            $img_name = "IMG_" . date("Ymd") . "_" . hash('md5', $img_tmp_name) . "." . $img_ext;
            $img_path = UPLOAD_DIR . $img_name;
            $img_path_small = UPLOAD_DIR_SMALL . $img_name;

            if (move_uploaded_file($img_tmp_name, $img_path)) {
                $message["success"][] = "Изображение загруженно на сервер!";
            } else {
                throw new Exception("Не удалось загрузить изображение на сервер!");
            }

            if ($this->createThumbnail($img_path, $img_path_small, 200, 200)) {
                $message["success"][] = "Создана уменьшенная копия изображения!";
            } else {
                throw new Exception("Не удалось создать уменьшенную копию изображения!");
            }

            $answer = $this->uploadImgInDb($img_name);
            array_push($message["success"], ...$answer["success"]);
            $message["id_image"] = $answer['id_image'];
        } else
            throw new Exception("Файл не соответствует формату изображения или размер файла больше 2Mb!");

        return $message;
    }

    protected function createThumbnail(string $path, string $save, int $width, int $height)
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

    protected function uploadImgInDb(string $img_name): array
    {
        $message["success"] = [];

        if (empty($img_name)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $sql_query = "SELECT id_image FROM images WHERE name=? LIMIT 1;";
        $row = $this->db->fetchRow($sql_query, [$img_name]);

        if (!empty($row)) {
            $message["id_image"] = $row['id_image'];
            $message["success"][] = "Изображение уже существует в базе данных!";
            return $message;
        }

        $sql_query = "INSERT INTO images(name) VALUES (?);";
        $this->db->query($sql_query, [$img_name]);

        if ($message["id_image"] = $this->db->getConnection()->lastInsertId())
            $message["success"][] = "Изображение добавленно в базу данных!";
        else
            throw new Exception("Не удалось загрузить изображение в базу данных!");

        return $message;
    }

    public function createBondImg(int $id, int $id_image): array
    {
        $message["success"] = [];

        if ((empty($id)) || (empty($id_image))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id = (int)htmlspecialchars((int)strip_tags((int)$id));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT * FROM {$this->table_name[0]} WHERE $this->pole_name=? AND id_image=? LIMIT 1;";
        $answer = $this->db->fetchRow($sql_query, [$id, $id_image]);

        if (!empty($answer))
            $message["success"][] = "Связь между изображением и товаром уже существует!";
        else {
            $sql_query = "INSERT INTO {$this->table_name[0]}($this->pole_name, id_image) VALUES (?, ?);";
            $this->db->query($sql_query, [$id, $id_image]);

            $message["success"][] = "Созданна связь между изображением и товаром!";
        }

        return $message;
    }

    public function readImgAll(int $id): array
    {
        $message["success"] = [];
        $message["img"] = [];

        if (empty($id)) {
            throw new Exception("Передаваемый аргумент пуст!");
        }

        $id = (int)htmlspecialchars((int)strip_tags((int)$id));

        $sql_query = "SELECT i.id_image, i.name FROM {$this->table_name[0]} it
                        INNER JOIN images i ON it.id_image = i.id_image
                        WHERE it.$this->pole_name = ?;";
        $message["img"] = $this->db->fetchAll($sql_query, [$id]);

        if (!empty($message["img"]))
            $message["success"][] = "Изображения из базы данных получены!";
        else
            $message["success"][] = "В базе данных нет изображений!";

        return $message;
    }

    public function deleteImg(int $id, int $id_image): array
    {
        $message["success"] = [];

        if ((empty($id)) || (empty($id_image))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id = (int)htmlspecialchars((int)strip_tags((int)$id));
        $id_image = (int)htmlspecialchars((int)strip_tags((int)$id_image));

        $sql_query = "SELECT i.id_image, i.name FROM {$this->table_name[0]} it 
                        INNER JOIN images i ON it.id_image = i.id_image
                        WHERE it.$this->pole_name=? AND it.id_image=? LIMIT 1;";
        $answer = $this->db->fetchRow($sql_query, [$id, $id_image]);

        if (!empty($answer)) {
            $img_delete_name = UPLOAD_DIR . $answer['name'];
            $img_delete_name_small = UPLOAD_DIR_SMALL . $answer['name'];

            $sql_query = "SELECT id_img_main FROM {$this->table_name[1]} WHERE $this->pole_name=? AND id_img_main=? LIMIT 1;";
            $answer = $this->db->fetchRow($sql_query, [$id, $id_image]);

            $delete_img_main = false;
            if (!empty($answer))
                $delete_img_main = true;


            $sql_query = "DELETE FROM images WHERE id_image=?;";
            $this->db->query($sql_query, [$id_image]);

            unlink($img_delete_name);
            unlink($img_delete_name_small);
            $message["success"][] = "Изображение удалено из базы данных и сервера!";

            if ($delete_img_main) {
                $sql_query = "SELECT * FROM {$this->table_name[0]} WHERE $this->pole_name=? ORDER BY created LIMIT 1;";
                $row = $this->db->fetchRow($sql_query, [$id]);

                if (!empty($row)) {
                    $answer = $this->setMainImg($id, $row['id_image']);
                    array_push($message["success"], ...$answer["success"]);
                } else {
                    $sql_query = "UPDATE {$this->table_name[1]} SET id_img_main=null WHERE $this->pole_name=?;";
                    $this->db->query($sql_query, [$id]);

                    $message["success"][] = "Главное изображение пусто!";
                }
            }
        } else
            throw new Exception("Неверно указано изображение!");

        return $message;
    }

    public function setMainImg(int $id, int $id_img_main): array
    {
        $message["success"] = [];

        if ((empty($id)) || (empty($id_img_main))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id = (int)htmlspecialchars((int)strip_tags((int)$id));
        $id_img_main = (int)htmlspecialchars((int)strip_tags((int)$id_img_main));

        $sql_query = "SELECT id_image FROM {$this->table_name[0]} WHERE $this->pole_name=? AND id_image=? LIMIT 1;";
        $answer = $this->db->fetchRow($sql_query, [$id, $id_img_main]);

        if (!empty($answer)) {
            $sql_query = "UPDATE {$this->table_name[1]} SET id_img_main=? WHERE $this->pole_name=?;";
            $this->db->query($sql_query, [$id_img_main, $id]);

            $message["success"][] = "Главное изображение изменено!";
        } else
            throw new Exception("Неверно указан товар или изображение!");

        return $message;
    }

}