<?php

define("URL_BASE", "http://localhost:81/server-list");
define("DB_CONFIG", 'mysql:host=localhost;dbname=server-list');
define("DB_USER", 'root');
define("DB_PASS", '');
define("PDO_OPTIONS", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
]);

define('DISCORD_TOKEN', '');
define('OAUTH2_CLIENT_ID', '');
define('OAUTH2_CLIENT_SECRET', '');
define('SES_PATH', __DIR__ . '../storage/sessions/');
date_default_timezone_set("America/Sao_Paulo");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);
error_reporting(E_ALL);
