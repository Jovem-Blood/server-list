<?php
require __DIR__ . "/vendor/autoload.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);
$router->namespace("Source\App");

$router->group(null);

$router->get('/', "Web:home");


$router->get('/logout', function () {
    session_destroy();
    header('Location: ' . URL_BASE);
});

$router->get('/login', "Web:login");


$router->dispatch();
