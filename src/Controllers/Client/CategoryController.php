<?php 

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;
use Doctrine\DBAL\Schema\View;

/**
 * Lớp CategoryController quản lý các thao tác liên quan đến danh mục sản phẩm
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc xử lý các yêu cầu liên quan đến danh mục sản phẩm từ phía người dùng.
 */
class CategoryController extends Controller
{
    /**
     * @var Category Đối tượng Category để tương tác với cơ sở dữ liệu
     */
    private Category $category;

    /**
     * Khởi tạo đối tượng CategoryController
     */
    public function __construct()
    {
        $this->category = new Category();
    }

    /**
     * Hiển thị danh sách tất cả danh mục sản phẩm
     * 
     * @return View Trang hiển thị danh sách danh mục
     */
    public function index()
    {
        $title = 'Danh mục sản phẩm';
        $categories = $this->category->findALL();

        return view(
            'client.categories.index',
            compact('title', 'categories')
        );
    }
}