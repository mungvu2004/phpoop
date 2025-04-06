<?php

namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
class HomeController
{
    private Category $category;
    private Product $product;

    public function __construct()
    {
        $this->category = new Category();
        $this->product = new Product();
    }
    public function index()
    {
        $products = $this->product->findAll();
        return view('client.dashboard', 
            compact('products')
        );
    }
    public function category() {
        
        
    }

    
}