<?php

define("URL_BASE", "http://localhost:81/server-list");
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "127.0.0.1 ",
    "port" => "3306",
    "dbname" => "server-list",
    "username" => "root",
    "passwd" => "",
    "options" => [
        // PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

define('DISCORD_TOKEN','');
define('OAUTH2_CLIENT_ID', '');
define('OAUTH2_CLIENT_SECRET', '');
define('SES_PATH', __DIR__ . '../storage/sessions/');
date_default_timezone_set("America/Sao_Paulo");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);
error_reporting(E_ALL);