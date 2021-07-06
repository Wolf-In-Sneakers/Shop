<?php

namespace Shop\models\Auth;

use Exception;
use Shop\core\Model;

class Auth extends Model
{

    public function registration(string $login, string $passwd, string $check_passwd, string $name): bool
    {
        if ((empty($login)) || (empty($passwd)) || (empty($check_passwd)) || (empty($name)))
            throw new Exception("Не все поля заполнены!");

        $name = htmlspecialchars((string)strip_tags((string)trim((string)$name)));
        $login = htmlspecialchars((string)strip_tags((string)trim((string)$login)));

        if ($passwd !== $check_passwd)
            throw new Exception("Пароли не совпадают!");

        if (strlen($passwd) < 9)
            throw new Exception("Пароль меньше 9 символов!");

        $sql_query = "SELECT * FROM users WHERE login= ? LIMIT 1;";
        if (!empty($this->db->fetchRow($sql_query, [$login])))
            throw new Exception("Пользователь с таким логином уже существует!");

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        $sql_query = "INSERT INTO users(name, login, id_access) VALUES (?, ?, 2);
                        INSERT INTO passwords(id_user, password) VALUES (LAST_INSERT_ID(), ?);";
        $this->db->query($sql_query, [$name, $login, $passwd]);

        header('Location: /');
        return true;
    }

    public function login(string $login, string $passwd): array
    {
        $message = [];

        if ((empty($login)) || (empty($passwd))) {
            throw new Exception("Не все поля заполнены!");
        }

        $login = htmlspecialchars((string)strip_tags((string)trim((string)$login)));

        $sql_query = "SELECT u.*, p.password, p.modified as modified_passwd FROM users u
                        INNER JOIN passwords p ON u.id_user=p.id_user
                        WHERE login=? LIMIT 1;";
        ($row = $this->db->fetchRow($sql_query, [$login]));

        if (!empty($row)) {
            if (password_verify($passwd, $row['password'])) {
                if (session_start()) {
                    $id_user = $row['id_user'];

                    $_SESSION["user"] = array_filter($row, function ($key) {
                        return $key !== "password";
                    }, ARRAY_FILTER_USE_KEY);

                    $sql_query = "UPDATE users SET last_action=now() WHERE id_user= ?;";
                    $this->db->query($sql_query, [$row['id_user']]);

                    $sql_query = "SELECT p.id_product, p.name, price, b.quantity, i.name as image_name FROM basket b
                                    INNER JOIN products p ON p.id_product=b.id_product
                                    LEFT JOIN images i ON p.id_img_main = i.id_image
                                    WHERE b.id_user=?
                                    ORDER BY b.created;";
                    $basket_in_db = $this->db->fetchAll($sql_query, [$id_user]);

                    foreach ($basket_in_db as $key => $item) {
                        if (!empty($item["image_name"])) {
                            $basket_in_db[$key]["image_name"] = IMG_DIR_SMALL . $item["image_name"];
                        } else {
                            $basket_in_db[$key]["image_name"] = IMG_NOT_FOUND;
                        }
                    }


                    if (!empty($_SESSION["basket"])) {
                        $basket = $_SESSION["basket"];

                        foreach ($basket as $val_ses) {
                            foreach ($basket_in_db as $val_db) {
                                if ((int)$val_db["id_product"] === (int)$val_ses["id_product"]) {
                                    continue 2;
                                }
                            }

                            $sql_query = "INSERT INTO basket(id_user, id_product, quantity) VALUES (?, ?, ?);";
                            $this->db->query($sql_query, [$id_user, $val_ses["id_product"], $val_ses["quantity"]]);

                            $basket_in_db[] = $val_ses;
                        }
                    }

                    $_SESSION["basket"] = $basket_in_db;

                    session_write_close();
                    $message['success'] = true;
                } else
                    throw new Exception("Неудалось создать сессию!");

                return $message;
            }
        }
        throw new Exception("Неверный логин или пароль!");
    }
}