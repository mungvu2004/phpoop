<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;

/**
 * Lớp ProductController quản lý các thao tác liên quan đến sản phẩm từ phía người dùng
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc hiển thị, tìm kiếm và xem chi tiết sản phẩm.
 */
class ProductController extends Controller
{
    /**
     * @var Product Model xử lý dữ liệu sản phẩm
     */
    private Product $product;

    /**
     * @var Review Model xử lý dữ liệu đánh giá
     */
    private Review $review;

    /**
     * @var Size Model xử lý dữ liệu kích thước
     */
    private Size $size;

    /**
     * @var Category Model xử lý dữ liệu danh mục
     */
    protected $category;

    /**
     * @var Order Model xử lý dữ liệu đơn hàng
     */
    private Order $order;

    /**
     * @var string Tên cột ID người dùng
     */
    protected $idColumn = 'user_id';

    /**
     * Khởi tạo controller và các model liên quan
     */
    public function __construct()
    {
        $this->product = new Product();
        $this->review = new Review();
        $this->size = new Size();
        $this->category = new Category();
        $this->order = new Order();
    }

    /**
     * Hiển thị danh sách sản phẩm
     * 
     * @return mixed View hiển thị danh sách sản phẩm
     */
    public function index()
    {
        $products = $this->product->findALL();
        return view("client.products.list", compact("products"), "Danh sách sản phẩm");
    }

    /**
     * Hiển thị danh sách sản phẩm với danh mục
     * 
     * @return mixed View hiển thị danh sách sản phẩm và danh mục
     */
    public function listIndex()
    {
        $categories = $this->category->findAll();
        $products = $this->product->listProduct();

        return view('client.list-product', compact('products', 'categories'), 'Danh sách sản phẩm');
    }

    /**
     * Hiển thị chi tiết sản phẩm
     * 
     * @param int $id ID của sản phẩm cần xem chi tiết
     * @return mixed View hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        if(isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $orders = $this->order->findUser($user['id']);
        }
        else {
            $orders = [];
        }
        $productDetail = $this->product->find($id);
        $productReview = $this->review->review($id);
        $productSize = $this->size->selectAll($id);
        
        $title = $productDetail['name'] ?? 'Chi tiết sản phẩm';
       
        return view(
            'client.product-detail',
            compact('productDetail', 'productSize', 'productReview', 'orders'),
            $title
        );
    }

    /**
     * Tìm kiếm sản phẩm theo tên
     * 
     * @param string $text Từ khóa tìm kiếm
     * @return void Trả về kết quả dưới dạng JSON
     */
    public function search($text)
    {
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
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
