<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    private Product $product;
    protected $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index()
    {
        $products = $this->product->findALL();
        return view("client.products.list", compact("products"));
    }

    public function listIndex()
    {
        $title = 'Danh sách sản phẩm';
        $products = $this->product->findAll();
        $categories = $this->category->findAll(); // lấy danh mục

        return view(
            'client.list-product',
            compact('title', 'products', 'categories') // truyền thêm categories vào
        );
    }

    

    
}