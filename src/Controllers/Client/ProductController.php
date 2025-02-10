<?php

namespace App\Controllers\Client;

use App\Models\Product;
class ProductController
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

}