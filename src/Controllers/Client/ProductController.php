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
        $title = 'Trang danh sach san pham';
        $products = $this->product->findALL();

        return view(
            'client.products.index',
            compact('title', 'products')
        );
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