<?php

$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        echo 'Admin Area';
    });
    $router->get('/about', function() {
        echo 'Admin Area';
    });
});