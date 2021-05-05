<?php

namespace Shop\User;

use mysqli;

class User
{
    private mysqli $mysqli;
    private UserImg $user_img;

    private int $id_user;
    private string $name;
    private string $login;
    private int $id_access;
    private int $id_img_main;
    private string $last_action;
    private string $created;
    private string $modified;
    private string $modified_passwd;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->user_img = new UserImg($this->mysqli);

        $this->id_user = (int)htmlspecialchars((int)strip_tags((int)$_SESSION["user"]['id_user']));
        $this->name = $_SESSION["user"]['name'];
        $this->login = $_SESSION["user"]['login'];
        $this->id_access = $_SESSION["user"]['id_access'];
        $this->id_img_main = $_SESSION["user"]["id_img_main"] ?? 0;
        $this->last_action = $_SESSION["user"]["last_action"];
        $this->created = $_SESSION["user"]["created"];
        $this->modified = $_SESSION["user"]["modified"];
        $this->modified_passwd = $_SESSION["user"]["modified_passwd"];
    }

    public function read_profile(): array
    {
        $message = [];
        $message["success"] = [];

        $message["user"] = $_SESSION["user"];

        $answer = $this->user_img->read_img_all($this->id_user);
        array_push($message["success"], ...$answer["success"]);
        $message["img"] = $answer['img'];
        if (!empty($answer['error'])) {
            $message['error'] = $answer['error'];
        }

        return $message;
    }

    public function update_profile(string $name = "", string $login = "", array $imgs = []): array
    {
        $message = [];
        $message["success"] = [];

        $name = ((!empty($name)) and ((string)$name !== (string)$_SESSION["user"]['name'])) ? $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)$name))) : null;
        $login = ((!empty($login)) and ((string)$login !== (string)$_SESSION["user"]['login'])) ? $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)$login))) : null;

        $sql_query = 'UPDATE users SET ';
        $change_exists = false;
        if (isset($name)) {
            $sql_query .= "name='$name'";
            $change_exists = true;
        }
        if (isset($login)) {
            $sql_query .= ($change_exists) ? ", login='$login'" : "login='$login'";
            $change_exists = true;
        }
        if ($change_exists) {
            $sql_query .= " WHERE id_user=$this->id_user;";
            if (!$this->mysqli->query($sql_query)) {
                $message["error"] = "В запрос на изменение профиля произошла ошибка!";
                return $message;
            }
            if (isset($name))
                $_SESSION["user"]['name'] = $name;
            if (isset($login))
                $_SESSION["user"]['login'] = $login;
        }

        foreach ($imgs["size"] as $key => $img_size) {
            if ((!empty($img_size)) && (is_uploaded_file($imgs['tmp_name'][$key]))) {
                $answer = $this->user_img->upload_img($imgs['name'][$key], $imgs['tmp_name'][$key], $imgs['type'][$key], $img_size);
                array_push($message["success"], ...$answer["success"]);
                if (!empty($answer['error'])) {
                    $message['error'] = $answer['error'];
                    return $message;
                }
                $id_image = $answer['id_image'];

                if (empty($first_image))
                    $first_image = $id_image;

                $answer = $this->user_img->create_bond_user_img($this->id_user, $id_image);
                array_push($message["success"], ...$answer["success"]);
                if (!empty($answer['error'])) {
                    $message['error'] = $answer['error'];
                    return $message;
                }

            }
        }

        if (!empty($first_image)) {
            $sql_query = "SELECT id_img_main FROM users WHERE id_user=$this->id_user LIMIT 1;";
            if (!($answer = $this->mysqli->query($sql_query))) {
                $message["error"] = "Неудалось выполнить запрос на выбоку пользователя из базы данных!";
                return $message;
            }
            if ($row = $answer->fetch_assoc())
                if (empty($row['id_img_main'])) {
                    $answer = $this->user_img->set_main_img($this->id_user, $first_image);
                    array_push($message["success"], ...$answer["success"]);
                    if (!empty($answer['error'])) {
                        $message['error'] = $answer['error'];
                        return $message;
                    }
                }
        }

        $message["success"][] = "Профиль успешно изменен!";
        return $message;
    }

    public function change_passwd(string $last_passwd, string $passwd, string $check_passwd): array
    {
        $message = [];

        if ((empty($last_passwd)) || (empty($passwd)) || (empty($check_passwd))) {
            $message["error"] = "Не все поля заполнены!";
            return $message;
        }

        if (strlen($passwd) < 9) {
            $message["error"] = "Пароль меньше 9 символов!";
            return $message;
        }

        if ((string)$passwd !== (string)$check_passwd) {
            $message["error"] = "Пароли не совпадают!";
            return $message;
        }

        if ((string)$last_passwd === (string)$passwd) {
            $message["error"] = "Пароль не должен совпадать со старым паролем!";
            return $message;
        }

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        $sql_query = "SELECT password FROM passwords WHERE id_user=$this->id_user LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку пользователя из базы данных!";
            return $message;
        }
        if ($row = $answer->fetch_assoc()) {
            if (password_verify($last_passwd, $row["password"])) {
                $sql_query = "UPDATE passwords SET password='$passwd' WHERE id_user=$this->id_user;";
                if ($this->mysqli->query($sql_query))
                    $message["success"] = "Пароль успешно изменен!";
                else {
                    $message["error"] = "Неудалось изменить пароль!";
                    return $message;
                }
            } else {
                $message["error"] = "Неверно введен пароль!";
                return $message;
            }
        } else {
            $message["error"] = "Неудалось найти пользователя в базе данных!";
            return $message;
        }

        $sql_query = "SELECT modified FROM passwords WHERE id_user=$this->id_user LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку пользователя из базы данных!";
            return $message;
        }
        if ($row = $answer->fetch_assoc()) {
            $this->modified_passwd = $row['modified'];
            $_SESSION["user"]['modified_passwd'] = $row['modified'];
        }
        return $message;
    }

    public function delete_acc(string $passwd): array
    {
        $message = [];

        if (empty($passwd)) {
            $message["error"] = "Не введен пароль!";
            return $message;
        }

        $sql_query = "SELECT password FROM passwords WHERE id_user=$this->id_user LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку пользователя из базы данных!";
            return $message;
        }
        if ($row = $answer->fetch_assoc()) {
            if (password_verify($passwd, $row["password"])) {
                $sql_query = "DELETE FROM users WHERE id_user=$this->id_user;";
                if ($this->mysqli->query($sql_query)) {
                    $message["success"] = "Аккаунт успешно удален!";
                    session_destroy();
                } else
                    $message["error"] = "Неудалось удалить аккаунт!";
            } else
                $message["error"] = "Неверно введен пароль!";
        } else
            $message["error"] = "Неудалось найти пользователя в базе данных!";

        return $message;
    }
}
