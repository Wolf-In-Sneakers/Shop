<?php


namespace Shop\core;

use Exception;
use Shop\lib\DB\DB;

abstract class Model
{
    protected DB $db;

    public function __construct()
    {
        try {
            $this->db = DB::getInstance();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

}