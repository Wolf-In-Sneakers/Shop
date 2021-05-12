<?php

session_start();
$basket = $_SESSION["basket"] ?? null;
session_destroy();

session_start();
$_SESSION["basket"] = $basket;

header('Location: /index.php');
