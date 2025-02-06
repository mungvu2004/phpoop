<?php

namespace App\Models;

class Product 
{
    public static function getProducts()
    {
        return [
            [
                'name' => 'Product 1',
                'price' => 100
            ],
            [
                'name' => 'Product 2',
                'price' => 200
            ],
            [
                'name' => 'Product 3',
                'price' => 300
            ]
        ];
    }
}