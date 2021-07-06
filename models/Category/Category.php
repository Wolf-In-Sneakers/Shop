<?php


namespace Shop\models\Category;

use Shop\core\Model;

class Category extends Model
{
    const SECTIONS = ["women", "men", "kids", "accessories"];

    public function readCategories(string $section)
    {
        $message["success"] = [];

        if (!in_array($section, self::SECTIONS, true)) {
            throw new Exception("Страница не найдена!");
        }

        $sql_query = "SELECT sc.id, c.name, i.name as image_name FROM sections_categories sc
                        INNER JOIN categories c ON sc.id_category = c.id_category
                        INNER JOIN sections s on sc.id_section = s.id_section
                        LEFT JOIN images i on sc.id_image = i.id_image
                        WHERE s.name = ?";
        $message["categories"] = $this->db->fetchAll($sql_query, [$section]);

        $message["success"][] = "Категории успешно полученны из базы данных!";

        return $message;
    }

}