<?php

namespace Shop\lib\Singleton;

trait Singleton
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static final function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }
}