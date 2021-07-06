<?php

namespace Shop\models\Comment;

use Exception;
use Shop\core\Model;


class Comment extends Model
{
    public function addComment(int $id_product, string $author, string $comment): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($author)) || (empty($comment))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));
        $author = htmlspecialchars(strip_tags((string)trim((string)$author)));
        $comment = htmlspecialchars(strip_tags((string)trim((string)$comment)));

        $sql_query = "INSERT INTO comments(id_product, author, text) VALUES (?, ?, ?);";
        $this->db->query($sql_query, [$id_product, $author, $comment]);
        $message["success"][] = "Добавлен новый комментарий!";

        return $message;
    }

    public function readComments(int $id_product): array
    {
        $message["success"] = [];
        $message["comments"] = [];

        if (empty($id_product)) {
            $message["comments"]["error"] = "Передаваемый аргумент пуст!";
            return $message;
        }

        $id_product = (int)htmlspecialchars((int)strip_tags((int)$id_product));

        $sql_query = "SELECT * FROM comments WHERE id_product=?;";
        $message["comments"] = $this->db->fetchAll($sql_query, [$id_product]);

        if (!empty($message["comments"]))
            $message["success"][] = "Все комментарии получены!";
        else
            $message["success"][] = "Комментарии отсутствуют!";

        return $message;
    }

    public function deleteComment(int $id_product, int $id_comment): array
    {
        $message["success"] = [];

        if ((empty($id_product)) || (empty($id_comment))) {
            throw new Exception("Один или более передаваемых аргументов пусты!");
        }

        $sql_query = "SELECT * FROM comments WHERE id_product=? AND id_comment=? LIMIT 1;";
        $answer = $this->db->fetchRow($sql_query, [$id_product, $id_comment]);

        if ((!empty($answer))) {
            $sql_query = "DELETE FROM comments WHERE id_comment=? AND id_product=?;";
            $this->db->query($sql_query, [$id_comment, $id_product]);
            $message["success"][] = "Комментарий успешно удален!";
        } else
            throw new Exception("Неудалось найти комментарий в базе данных!");

        return $message;
    }
}