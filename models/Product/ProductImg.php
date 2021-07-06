<?php

namespace Shop\models\Product;

use Shop\lib\Image\Image;

class ProductImg extends Image
{

    protected array $table_name = [];
    protected string $pole_name;

    public function __construct()
    {
        parent::__construct();

        $this->table_name = ["images_products", "products"];
        $this->pole_name = "id_product";
    }

}