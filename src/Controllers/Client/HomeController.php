<?php

namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Category;
class HomeController
{
    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }
    public function index()
    {
        return view('client.layouts.main');
    }
    public function category() {
        
        
    }

    
}