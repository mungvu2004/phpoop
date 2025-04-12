<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function index()
    {
        $products = $this->product->findALL();
        return view("client.products.list", compact("products"));
    }

    public function listIndex()
    {
        $title = ' Danh sach san pham';
        $products = $this-> product ->findAll();
        return view(
            'client.list-product',
            compact('title','products')
        );
    }

    

    
}