<?php

namespace Shop\Comment;

use mysqli;

class Comment
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function add_comment(int $id_product, string $author, string $comment): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($id_product)) || (empty($author)) || (empty($comment))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $author = $this->mysqli->real_escape_string((string)htmlspecialchars(strip_tags((string)trim((string)$author))));
        $comment = $this->mysqli->real_escape_string((string)htmlspecialchars(strip_tags((string)trim((string)$comment))));

        $sql_query = "INSERT INTO comments(id_product, author, text) VALUES ($id_product,'$author','$comment');";
        if ($this->mysqli->query($sql_query))
            $message["success"][] = "Добавлен новый комментарий!";
        else
            $message["error"] = "Неудалось добавить новый комментарий!";

        return $message;
    }

    public function read_comments(int $id_product): array
    {
        $message = [];
        $message["success"] = [];
        $message["comments"] = [];

        if (empty($id_product)) {
            $message["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT * FROM comments WHERE id_product=$id_product;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку комментарииев из базы данных!";
            return $message;
        }

        while ($row = $answer->fetch_assoc())
            $message["comments"][] = $row;

        if (!empty($message["comments"]))
            $message["success"][] = "Все комментарии получены!";
        else
            $message["success"][] = "Комментарии отсутствуют!";

        return $message;
    }

    public function delete_comment(int $id_product, int $id_comment): array
    {
        $message = [];
        $message["success"] = [];

        if ((empty($id_product)) || (empty($id_comment))) {
            $message["error"] = "Один или более передаваемых аргументов пусты!";
            return $message;
        }

        $sql_query = "SELECT * FROM comments WHERE id_product=$id_product AND id_comment=$id_comment LIMIT 1;";
        if (!($answer = $this->mysqli->query($sql_query))) {
            $message["error"] = "Неудалось выполнить запрос на выбоку комментарииев из базы данных!";
            return $message;
        }

        if (($answer->fetch_assoc())) {
            $sql_query = "DELETE FROM comments WHERE id_comment=$id_comment AND id_product=$id_product;";
            if ($this->mysqli->query($sql_query))
                $message["success"][] = "Комментарий успешно удален!";
            else
                $message["error"] = "Неудалось удалить комментарий!";
        } else
            $message["error"] = "Неудалось найти комментарий в базе данных!";

        return $message;
    }
}