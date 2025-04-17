<?php

namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
class HomeController
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }
    public function index()
    {
        $product4 = $this->product->paginate(1, 4);
        return view(
            'client.dashboard',
            compact('product4')
        );
    }    
}