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
        $categories = $this->category->findAll();
        $products = $this->product->findAll();


        // Lấy dữ liệu từ URL
        $categoryId = $_GET['category'] ?? null;
        $maxPrice = $_GET['price'] ?? null;
        $sort = $_GET['sort'] ?? null;

        // Lọc sản phẩm theo danh mục
        if ($categoryId) {
            $products = array_filter($products, function ($product) use ($categoryId) {
                return $product['category_id'] == $categoryId;
            });
        }

        // Lọc theo giá
        if ($maxPrice) {
            $products = array_filter($products, function ($product) use ($maxPrice) {
                return $product['price'] <= $maxPrice;
            });
        }

        // Sắp xếp
        switch ($sort) {
            case 'price_asc':
            usort($products, fn($a, $b) => $a['price'] <=> $b['price']);
            break;
        case 'price_desc':
            usort($products, fn($a, $b) => $b['price'] <=> $a['price']);
            break;
        default:
            // Mặc định không sắp xếp
            break;
        }

        // Lấy tên danh mục (nếu có)
        $categoryName = null;
        if ($categoryId) {
            foreach ($categories as $cat) {
                if ($cat['id'] == $categoryId) {
                    $categoryName = $cat['name'];
                    break;
                }
            }
        }

        // Tính max price để set cho thanh trượt
        $allPrices = array_column($this->product->findAll(), 'price');
        $maxAvailablePrice = $allPrices ? max($allPrices) : 1000000;

        return view('client.list-product', [
            'title' => $title,
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'maxPrice' => $maxAvailablePrice,
            ]);
        
    
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