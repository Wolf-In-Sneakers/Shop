<?php

namespace Shop\DB;

use mysqli;
use Shop\Singleton\Singleton;

final class DB
{
    use Singleton;

    private mysqli $mysqli;

    private function __construct()
    {
        $this->mysqli = new mysqli(HOST, USER, PASS, DB);
        if ($this->mysqli->connect_errno) {
            throw new \Error("Не удалось подключиться к MySQL: " . $this->mysqli->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->mysqli;
    }
}
