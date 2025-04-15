<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;

class ProductController extends Controller
{
    private Product $product;
    private Review $review;
    private Size $size;
    protected $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->review = new Review();
        $this->size = new Size();
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
    public function show($id) {
        $productDetail = $this->product->find($id);
        $productReview = $this->review->review($id);
        $productSize = $this->size->selectAll($id);
        return view('client.product-detail', 
            compact('productDetail', 'productSize', 'productReview'));
    }
    public function search($text) {
        header('Content-Type: application/json');
        try {
            // Tìm kiếm sản phẩm
            $products = $this->product->searchProductsByName($text);
            if (empty($products)) {
                echo json_encode([]);  
            } else {
                foreach ($products as &$product) {
                    $product['image_url'] = file_exists($product['image_url']) ? file_url($product['image_url']) : '/storage/uploads/users/error.png';
                }
                echo json_encode($products);  
            }
        } catch (\Exception $e) {
            echo json_encode(['error'=> $e->getMessage()]);
        }
    }
    
    
}