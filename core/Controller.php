<?php


namespace Shop\core;

use Shop\models\Auth\Auth;
use Shop\models\Basket\Basket;


abstract class Controller
{
    protected Auth $auth;
    protected Basket $basket;
    protected View $view;

    protected array $errors;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->basket = new Basket();
        $this->view = new View();

        $this->errors = [];
    }


}