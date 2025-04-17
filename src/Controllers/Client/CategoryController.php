<?php 

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;
use Doctrine\DBAL\Schema\View;

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
}