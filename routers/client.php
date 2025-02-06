<?php

use App\Controllers\Client\HomeController;

$router->get('/', HomeController::class . '@index');

$router->get('/about', function() {
    echo 'Client Area';
});