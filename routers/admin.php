<?php

use App\Controllers\Admin\UserController;

$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        echo 'Admin Area';
    });
    $router->get('/users', UserController::class . '@index');

    $router->post('/users/testUploadFile', UserController::class . '@testUploadFile');
});