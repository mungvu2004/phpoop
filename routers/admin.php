<?php

use App\Controllers\Admin\UserController;

$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        return view('admin.dashboard');
    });
    // $router->get('/', function() {
    //     return view('elements.dashboard-login');
    // });
    
    $router->post('/users', UserController::class . '@account');

    $router->post('/users/testUploadFile', UserController::class . '@testUploadFile');
});

$router->mount('/login', function() use ($router) {
    $router->get('/', function() {
        return view('elements.dashboard-login');
    });
    $router->post('/register', UserController::class . '@signUp');
});