<?php

return [

    "" => [
        "controller" => "main",
        "action" => "index"
    ],

    "(men|women|kids|accessories)" => [
        "controller" => "category",
        "action" => "index",
        "params" => "$1"
    ],

    "category/([0-9]+)" => [
        "controller" => "product",
        "action" => "readGoodsCategory",
        "params" => "$1"
    ],

    "featured" => [
        "controller" => "product",
        "action" => "readGoodsFeatured"
    ],

    "product/([0-9]+)" => [
        "controller" => "product",
        "action" => "index",
        "params" => "$1"
    ],

    "product/like/([0-9]+)" => [
        "controller" => "product",
        "action" => "like",
        "params" => "$1"
    ],

    "search" => [
        "controller" => "product",
        "action" => "search"
    ],

    "basket" => [
        "controller" => "basket",
        "action" => "index"
    ],

    "basket/add/([0-9]+)" => [
        "controller" => "basket",
        "action" => "add",
        "params" => "$1"
    ],

    "basket/change/([0-9]+)" => [
        "controller" => "basket",
        "action" => "change",
        "params" => "$1"
    ],

    "basket/delete/([0-9]+)" => [
        "controller" => "basket",
        "action" => "delete",
        "params" => "$1"
    ],

    "basket/clear" => [
        "controller" => "basket",
        "action" => "clear"
    ],

    "order" => [
        "controller" => "order",
        "action" => "index"
    ],

    "account" => [
        "controller" => "account",
        "action" => "index"
    ],

    "account/login" => [
        "controller" => "account",
        "action" => "login"
    ],

    "account/registration" => [
        "controller" => "account",
        "action" => "registration"
    ],

    "account/change_passwd" => [
        "controller" => "account",
        "action" => "changePasswd"
    ],

    "account/delete_account" => [
        "controller" => "account",
        "action" => "deleteAccount"
    ],

    "account/exit" => [
        "controller" => "account",
        "action" => "exit"
    ],

];
