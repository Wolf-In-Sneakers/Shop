<?php

namespace Shop\Auth;

use mysqli;
use Shop\Basket\Basket;

class Auth
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function registration(string $login, string $passwd, string $check_passwd, string $name): ?string
    {
        if ((empty($login)) || (empty($passwd)) || (empty($check_passwd)) || (empty($name)))
            return "Не все поля заполнены!";

        $name = $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)trim((string)$name))));
        $login = $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)trim((string)$login))));


        if ($passwd !== $check_passwd)
            return "Пароли не совпадают!";

        if (strlen($passwd) < 9)
            return "Пароль меньше 9 символов!";

        $sql_query = "SELECT * FROM users WHERE login='$login' LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query)))
            return "Неудалось выполнить запрос на выбоку пользователя из базы данных!";

        if ($answer->fetch_assoc())
            return "Пользователь с таким логином уже существует!";

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $sql_query = "INSERT INTO users(name, login, id_access) VALUES ('$name','$login', 2);";
        if (!$this->mysqli->query($sql_query))
            return "Неудалось выполнить запрос на добавление пользователя в базу данных!";

        $sql_query = "INSERT INTO passwords(id_user, password) VALUES (LAST_INSERT_ID(),'$passwd');";
        if (!$this->mysqli->query($sql_query))
            return "Неудалось выполнить запрос на добавление пользователя в базу данных!";

        header('Location: index.php');
        return true;
    }

    public function login(string $login, string $passwd): array
    {
        $message = [];

        if ((empty($login)) || (empty($passwd))) {
            $message['error'] = "Не все поля заполнены!";
            return $message;
        }

        $login = $this->mysqli->real_escape_string((string)htmlspecialchars((string)strip_tags((string)trim((string)$login))));

        $sql_query = "SELECT u.*, p.password, p.modified as modified_passwd FROM users u
                        INNER JOIN passwords p ON u.id_user=p.id_user
                        WHERE login='$login' LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку пользователя из базы данных!";
            return $message;
        }

        if ($row = $answer->fetch_assoc()) {
            if (password_verify($passwd, $row['password'])) {
                if (session_start()) {
                    $id_user = $row['id_user'];
                    $_SESSION["user"]['id_user'] = $row['id_user'];
                    $_SESSION["user"]['name'] = $row['name'];
                    $_SESSION["user"]['login'] = $row['login'];
                    $_SESSION["user"]['id_access'] = $row['id_access'];
                    $_SESSION["user"]['id_img_main'] = $row['id_img_main'];
                    $_SESSION["user"]['last_action'] = $row['last_action'];
                    $_SESSION["user"]['created'] = $row['created'];
                    $_SESSION["user"]['modified'] = $row['modified'];
                    $_SESSION["user"]['modified_passwd'] = $row['modified_passwd'];

                    $sql_query = "UPDATE users SET last_action=now() WHERE id_user={$row['id_user']};";
                    if (!$this->mysqli->query($sql_query)) {
                        $message["error"] = "Неудалось выполнить запрос на обновление данных пользователя!";
                        return $message;
                    }

                    $sql_query = "SELECT p.id_product, p.name, price, b.quantity, i.name as image_name FROM basket b
                                    INNER JOIN products p ON p.id_product=b.id_product
                                    LEFT JOIN images i ON p.id_img_main = i.id_image
                                    WHERE b.id_user=$id_user
                                    ORDER BY b.created;";
                    if (!($answer = $this->mysqli->query($sql_query))) {
                        $message["error"] = "Неудалось выполнить запрос на выбоку товаров из базы данных!";
                        return $message;
                    }

                    $basket_in_db = [];
                    while ($row = $answer->fetch_assoc())
                        $basket_in_db[] = $row;

                    if (!empty($_SESSION["basket"])) {
                        $basket = $_SESSION["basket"];

                        $bask = new Basket($this->mysqli);

                        /*foreach ($basket_in_db as $key_db => $val_db) {
                            foreach ($basket as $val) {
                                if ((int)$val_db["id_product"] === (int)$val["id_product"]) {
                                    $basket_in_db[$key_db]["quantity"] += $val_db["quantity"];
                                    $answer = $bask->change_quantity($val_db["id_product"], $basket_in_db[$key_db]["quantity"]);
                                    if (!empty($answer['error'])) {
                                        $message['error'] = $answer['error'];
                                        return $message;
                                    }

                                    continue 2;
                                }
                            }
                        }*/

                        foreach ($basket as $val_ses) {
                            foreach ($basket_in_db as $val_db) {
                                if ((int)$val_db["id_product"] === (int)$val_ses["id_product"]) {
                                    continue 2;
                                }
                            }

                            $sql_query = "INSERT INTO basket(id_user, id_product, quantity) VALUES ($id_user, {$val_ses["id_product"]}, {$val_ses["quantity"]});";
                            if (!$this->mysqli->query($sql_query)) {
                                $message["error"] = "Неудалось выполнить запрос на добавление товара в корзину!";
                                return $message;
                            }

                            $basket_in_db[] = $val_ses;
                        }
                    }

                    $_SESSION["basket"] = $basket_in_db;

                    session_write_close();
                    $message['success'] = true;
                } else
                    $message['error'] = "Неудалось создать сессию!";

                return $message;
            }
        }
        $message['error'] = "Неверный логин или пароль!";
        return $message;
    }
}