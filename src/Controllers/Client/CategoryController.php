<?php 

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        $title = 'Danh mục sản phẩm';
        $categories = $this->category->findALL();

        return view(
            'client.categories.index',
            compact('title', 'categories')
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