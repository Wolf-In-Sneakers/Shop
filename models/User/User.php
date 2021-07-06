<?php

namespace Shop\models\User;

use Exception;
use Shop\core\Model;

class User extends Model
{
    private UserImg $user_img;

    private int $id_user;

    public function __construct()
    {
        parent::__construct();

        $this->user_img = new UserImg();

        $this->id_user = (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]['id_user']));
    }

    public function readProfile(): array
    {
        $message["success"] = [];
        $message["user"] = $_SESSION["user"];

        try {
            $answer = $this->user_img->readImgAll($this->id_user);
            array_push($message["success"], ...$answer["success"]);
            $message["img"] = $answer['img'];

            foreach ($message["img"] as $key => $item) {
                $message["img"][$key]["name"] = IMG_DIR . $item["name"];
            }

        } catch (Exception $e) {
            $message["user"]['error'] = $e->getMessage();
        }

        return $message;
    }

    public function updateProfile(string $name = "", string $login = "", array $imgs = []): array
    {
        $message["success"] = [];

        $name = ((!empty($name)) and ((string)$name !== (string)$_SESSION["user"]['name'])) ? htmlspecialchars((string)strip_tags((string)$name)) : $_SESSION["user"]['name'];
        $login = ((!empty($login)) and ((string)$login !== (string)$_SESSION["user"]['login'])) ? htmlspecialchars((string)strip_tags((string)$login)) : $_SESSION["user"]['login'];

        $sql_query = "UPDATE users SET name=?, login=?  WHERE id_user=?;";
        $this->db->query($sql_query, [$name, $login, $this->id_user]);

        if (isset($name))
            $_SESSION["user"]['name'] = $name;
        if (isset($login))
            $_SESSION["user"]['login'] = $login;

        foreach ($imgs["size"] as $key => $img_size) {
            if ((!empty($img_size)) && (is_uploaded_file($imgs['tmp_name'][$key]))) {
                $answer = $this->user_img->uploadImg($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                $id_image = $answer['id_image'];

                if (empty($first_image))
                    $first_image = $id_image;

                $answer = $this->user_img->createBondImg($this->id_user, $id_image);
                array_push($message["success"], ...$answer["success"]);
            }
        }

        if (!empty($first_image)) {
            $sql_query = "SELECT id_img_main FROM users WHERE id_user=? LIMIT 1;";
            $row = $this->db->fetchRow($sql_query, [$this->id_user]);
            if (!empty($row))
                if (empty($row['id_img_main'])) {
                    $answer = $this->user_img->setMainImg($this->id_user, $first_image);
                    array_push($message["success"], ...$answer["success"]);
                }
        }

        $message["success"][] = "Профиль успешно изменен!";
        return $message;
    }

    public function changePasswd(string $last_passwd, string $passwd, string $check_passwd): array
    {
        if ((empty($last_passwd)) || (empty($passwd)) || (empty($check_passwd))) {
            throw new Exception("Не все поля заполнены!");
        }

        if (strlen($passwd) < 9) {
            throw new Exception("Пароль меньше 9 символов!");
        }

        if ((string)$passwd !== (string)$check_passwd) {
            throw new Exception("Пароли не совпадают!");
        }

        if ((string)$last_passwd === (string)$passwd) {
            throw new Exception("Пароль не должен совпадать со старым паролем!");
        }

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        $sql_query = "SELECT password FROM passwords WHERE id_user=? LIMIT 1;";
        $row = $this->db->fetchRow($sql_query, [$this->id_user]);

        if (!empty($row)) {
            if (password_verify($last_passwd, $row["password"])) {
                $sql_query = "UPDATE passwords SET password=? WHERE id_user=?;";
                $this->db->query($sql_query, [$passwd, $this->id_user]);

                $message["success"] = "Пароль успешно изменен!";
            } else {
                throw new Exception("Неверно введен пароль!");
            }
        } else {
            throw new Exception("Неудалось найти пользователя в базе данных!");
        }

        $sql_query = "SELECT modified FROM passwords WHERE id_user=? LIMIT 1;";
        $row = $this->db->fetchRow($sql_query, [$this->id_user]);

        if (!empty($row)) {
            $this->modified_passwd = $row['modified'];
            $_SESSION["user"]['modified_passwd'] = $row['modified'];
        }

        return $message;
    }

    public function deleteAcc(string $passwd): array
    {
        $message = [];

        if (empty($passwd)) {
            throw new Exception("Не введен пароль!");
        }

        $sql_query = "SELECT password FROM passwords WHERE id_user=? LIMIT 1;";
        $row = $this->db->fetchRow($sql_query, [$this->id_user]);

        if (!empty($row)) {
            if (password_verify($passwd, $row["password"])) {
                $sql_query = "DELETE FROM users WHERE id_user=?;";
                $this->db->query($sql_query, [$this->id_user]);

                $message["success"] = "Аккаунт успешно удален!";
                session_destroy();
            } else
                throw new Exception("Неверно введен пароль!");
        } else
            throw new Exception("Неудалось найти пользователя в базе данных!");

        return $message;
    }
}
