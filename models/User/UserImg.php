<?php

namespace Shop\models\User;

use Shop\lib\Image\Image;

class UserImg extends Image
{
    protected array $table_name = [];
    protected string $pole_name;

    public function __construct()
    {
        parent::__construct();

        $this->table_name = ["images_users", "users"];
        $this->pole_name = "id_user";
    }
}