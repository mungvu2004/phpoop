<?php

namespace App\Controllers\Admin;

use App\Models\Category;

/**
 * Lớp CategoryController quản lý các thao tác liên quan đến danh mục sản phẩm
 * 
 * Lớp này cung cấp các phương thức đặc thù cho việc quản lý danh mục sản phẩm
 * từ phía quản trị viên, bao gồm thêm, sửa, xóa và xem danh sách danh mục.
 */
class CategoryController {
    /**
     * @var Category Đối tượng Category để tương tác với cơ sở dữ liệu
     */
    private Category $category;

    /**
     * Khởi tạo đối tượng CategoryController
     */
    public function __construct() {
        $this->category = new Category();
    }
    public function index() {
        $categories = $this->category->findALL();
        return view("", compact("categories"));
    }
}