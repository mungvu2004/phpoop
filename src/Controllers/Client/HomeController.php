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
        $categories = $this->category->findALL();
        return view('client.dashboard', compact('categories'));
    }
    public function category() {
        
        
    }

    
}