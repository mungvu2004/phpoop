<?php

use App\Controllers\Admin\UserController;

$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        return view('elements.login.login');
    });
    $router->post('/users', UserController::class . '@account');

    $router->post('/users/testUploadFile', UserController::class . '@testUploadFile');
});