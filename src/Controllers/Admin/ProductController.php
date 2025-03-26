<?php
namespace App\Controllers\Admin;

use App\Models\Product;
use App\Controller;

class ProductController extends Controller{

    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function index() {
        
        $product = $this->product->paginate();

        return view(
            "admin.product", 
            compact("product")    
        );
    }

    public function create() {
        echo "Form thêm dữ liệu.";
    }

    public function store() {
        echo "Xử lý thêm dữ liệu.";
    }

    public function edit($id) {
        echo "Chỉnh sửa dữ liệu ID: $id";
    }

    public function update($id) {
        echo "Cập nhật dữ liệu ID: $id";
    }

    public function delete($id) {
        echo "Xóa dữ liệu ID: $id";
    }
}