<?php

session_start();

require_once "../config/img.php";
require_once '../vendor/autoload.php';

use Shop\core\Router;

$router = new Router();

$router->run();
