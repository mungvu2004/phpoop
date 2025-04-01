<?php
namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Controller;
use Rakit\Validation\Rules\UploadedFile;

class ProductController extends Controller{

    private Product $product;
    private Category $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index() {
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $product = $this->product->paginate($page);
        $category = $this->category->findALL();

        return view(
            "admin.products.product", 
            compact("product", "category")    
        );
    }
    public function show($id) {
        $product = $this->product->find($id);
        return view(
            'admin.product.product-detail',
            compact('product')
        );
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $name = trim($_POST['name'] ?? '');
            $category_id = $_POST['category_id'] ?? '';
            $price = $_POST['price'] ?? '';
            $stock = $_POST['stock_quantity'] ?? '';
            $img = $_FILES['image'] ?? null;
            $active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1; // 1 = Có, 0 = Không
            $description = trim($_POST['description'] ?? '');
    
            // Mảng chứa lỗi
            $error = [];
    
            // Kiểm tra dữ liệu đầu vào
            if (empty($name)) {
                $error[] = "Tên sản phẩm không được để trống!";
            }
            if (empty($category_id) || !is_numeric($category_id)) {
                $error[] = "Danh mục sản phẩm không hợp lệ!";
            }
            if (!is_numeric($price) || $price < 0) {
                $error[] = "Đơn giá phải là số và không được âm!";
            }
            if (!is_numeric($stock) || floor($stock) != $stock || $stock < 0) {
                $error[] = "Số lượng phải là số nguyên không âm!";
            }
            if (strlen($description) > 500) {
                $error[] = "Mô tả không được vượt quá 500 ký tự!";
            }
            if (!in_array($active, [0, 1])) {
                $error[] = "Trạng thái không hợp lệ!";
            }
    
            // Xử lý upload file
            $img_path = '';
            if ($img && $img['error'] == UPLOAD_ERR_OK) {
                try {
                    $img_path = $this->uploadFile($img, 'products');
                } catch (\Exception $e) {
                    $error[] = "Lỗi khi upload file: " . $e->getMessage();
                }
            } else {
                $error[] = "Vui lòng chọn file ảnh!";
            }
    
            // Nếu có lỗi, chuyển hướng về form với thông báo lỗi
            if (!empty($error)) {
                $_SESSION['errors'] = $error;
                header("Location: /admin/product/create");
                exit;
            }
            // Chuẩn bị dữ liệu để chèn vào cơ sở dữ liệu
            $data = [
                "name" => $name,
                "category_id" => (int)$category_id,
                "price" => (float)$price, // hoặc (int) tùy cấu trúc bảng
                "stock_quantity" => (int)$stock,
                "image_url" => $img_path,
                "is_active" => $active,
                "description" => $description
            ];
    
            // Thử chèn dữ liệu vào cơ sở dữ liệu
            try {
                $idProduct = $this->product->insert($data);
                if ($idProduct > 0) {
                    $_SESSION['success'] = ["Thêm sản phẩm thành công! ID sản phẩm: $idProduct"];
                    header("Location: /admin/products");
                    exit;
                } else {
                    $_SESSION['errors'] = ["Không thể thêm sản phẩm vào cơ sở dữ liệu!"];
                    header("Location: /admin/product/create");
                    exit;
                }
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Lỗi cơ sở dữ liệu: " . $e->getMessage()];
                header("Location: /admin/product/create");
                exit;
            }
        }
        else{
            // Nếu không phải POST, trả về lỗi hoặc chuyển hướng
            header("Location: /admin/product");
            exit;
        }
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