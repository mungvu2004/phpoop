<?php

use App\Controller;
use Bramus\Router\Router;

$router = new Router();

require 'admin.php';
require 'client.php';
require 'web.php';
$router->mount('/logout', function() use ($router){
    $router->get('/', Controller::class . '@logout');
});
$router->run();