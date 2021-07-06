<?php


namespace Shop\core;


class Router
{
    private array $routes;
    private array $params;

    public function __construct()
    {
        $arr = require_once "../config/routers.php";

        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    private function add(string $route, array $params = [])
    {
        if (!empty($params)) {
            $route = "#^$route$#";
            $this->routes[$route] = $params;
        }
    }

    private function match(): bool
    {
        $path = trim($_SERVER['REQUEST_URI'], "/");

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $path)) {
                $str_params = preg_replace($route, $params["params"], $path);
                $params["params"] = explode(",", $str_params);
                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = "Shop\\controllers\\" . ucfirst($this->params["controller"]) . "Controller";
            if (class_exists($path)) {
                $controller = new $path;
                $action = $this->params["action"] . "Action";
                if (method_exists($controller, $action)) {
                    $controller->$action(...$this->params["params"]);
                } else {
                    echo "404";
                }
            } else {
                echo "404";
            }
        } else {
            echo "404";
        }
    }

}