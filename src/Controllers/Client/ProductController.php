<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;

class ProductController extends Controller
{
    private Product $product;
    private Review $review;
    private Size $size;

    public function __construct()
    {
        $this->product = new Product();
        $this->review = new Review();
        $this->size = new Size();
    }

    public function index()
    {
        $products = $this->product->findALL();
        return view("client.products.list", compact("products"));
    }

    public function listIndex()
    {
        $title = 'Danh sach san pham';
        $products = $this->product->findAll();
        return view(
            'client.list-product',
            compact('title','products')
        );
    }
    public function show($id) {
        $productDetail = $this->product->find($id);
        $productReview = $this->review->review($id);
        $productSize = $this->size->selectAll($id);
        return view('client.product-detail', 
            compact('productDetail', 'productSize', 'productReview'));
    }

    

    
}