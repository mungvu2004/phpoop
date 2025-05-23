<?php

use eftec\bladeone\BladeOne;

if(!function_exists('view')) {
    function view($view, $data = [], $title = null)
    {
        $views = __DIR__ . '/views';
        $cache = __DIR__ . '/storage/compiles';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        
        // Thêm title vào data nếu được truyền vào và chưa tồn tại trong data
        if ($title && !isset($data['title'])) {
            $data['title'] = $title;
        }
        
        echo $blade->run($view, $data);
    }
}
if(!function_exists('file_url')) {
    function file_url($path)
    {
        if(!file_exists($path)) {
            return null;
        }
        $version = filemtime($path);
        return $_ENV['APP_URL'] . $path . '?v=' . $version;
    }
}
if (!function_exists('route_url')) {
    function route_url($path)
    {
        $baseUrl = $_ENV['APP_URL'];
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
if(!function_exists('debug')){
    function debug(...$data){
        echo '<pre>';
        print_r($data);
        die;
    }
}

if(!function_exists('slug')) {
    function slug($string, $separetor = '-')
    {
        $string = mb_strtolower($string, 'UTF-8');

        $string = preg_replace('/[^\p{L}\p{N}\s]/u', '', $string);
        $string = preg_replace('/[\s]+/', $separetor, $string);

        $string = trim($string, $separetor) . '-' . random_string(6);

        return $string;
    }
}

if(!function_exists('random_string')) {
    function random_string($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}