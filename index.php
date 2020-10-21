<?php
require __DIR__ . "/vendor/autoload.php";
session_start();

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);
$router->namespace("Source\App");

$router->group(null);

$router->get('/', "Web:home");

$router->get('/login', "Web:login");

$router->get('/logout', "Web:logout");

$router->get('/profile', "Web:profile");


/*
 * Servers
 * */

$router->group('servers');

$router->get('/{serverId}', "Web:server");

$router->get('/{serverId}/vote',"Web:vote");

$router->get('/{serverId}/join',"Web:join");

/*
 * Errors and Dispatch
 */

$router->group("ooops");


$router->get("/{errcode}", "web:error");


$router->dispatch();

if ($router->error()) {
    $router->redirect("/ooops/{$router->error()}");
}
