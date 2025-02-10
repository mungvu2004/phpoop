<?php

use App\Controllers\Client\HomeController;
use App\Controllers\Client\ProductController;

// $router->get('/', HomeController::class . '@index');

$router->get('/about', function() {
    echo 'Client Area';
});

$router->get('/products', ProductController::class . "@index");