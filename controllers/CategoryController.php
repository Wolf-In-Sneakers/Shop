<?php


namespace Shop\controllers;

use Exception;
use Shop\core\Controller;
use Shop\models\Category\Category;


class CategoryController extends Controller
{

    private Category $category;

    public function __construct()
    {
        parent::__construct();

        $this->category = new Category();
    }

    public function indexAction(string $category)
    {
        try {
            $read_categories = $this->category->readCategories($category);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        $path = "/category/categories.tmpl";
        $vars = [
            "user" => $_SESSION["user"],
            "basket" => $_SESSION["basket"],
            "errors" => $this->errors,
            "categories" => $read_categories["categories"]
        ];

        $this->view->render($path, $vars);
    }

}