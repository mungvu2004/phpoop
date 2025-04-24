<?php

namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Category;

/**
 * Lớp HomeController quản lý các thao tác liên quan đến trang chủ
 * 
 * Lớp này xử lý các chức năng hiển thị trang chủ và các thông tin liên quan
 * như danh sách sản phẩm và đánh giá.
 */
class HomeController
{
    /**
     * @var Product Model xử lý dữ liệu sản phẩm
     */
    private Product $product;

    /**
     * Khởi tạo controller và model Product
     */
    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * Hiển thị trang chủ với danh sách sản phẩm và đánh giá
     * 
     * @return mixed View hiển thị trang chủ
     */
    public function index()
    {
        $product4 = $this->product->getAll();
        $ratings = $this->product->rating();
        return view(
            'client.dashboard',
            compact('product4', 'ratings'),
            'Trang chủ - PWShop'
        );
    }    
}