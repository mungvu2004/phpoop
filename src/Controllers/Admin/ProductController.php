<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Size;
use App\Controller;

class ProductController extends Controller
{

    private Product $product;
    private Category $category;
    private Review $review;
    private Size $size;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
        $this->review = new Review();
        $this->size = new Size();
    }

    public function index()
    {

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);

        $product = $this->product->paginate($page);
        $category = $this->category->findALL();

        return view(
            "admin.products.product",
            compact("product", "category")
        );
    }
    public function show($id)
    {
        $review = $this->review->review($id);
        $product = $this->product->find($id);
        $sizes = $this->size->selectAll($id);

        if (!$product) {
            $_SESSION['errors'] = ["Không tìm thấy sản phẩm!"];
            header("Location: /admin/product");
            exit;
        }

        return view(
            'admin.products.product-detail',
            compact('product', 'review', 'sizes')
        );
    }
    public function create()
    {
            // Lấy dữ liệu từ form
            $name = trim($_POST['name'] ?? '');
            $category_id = $_POST['category_id'] ?? '';
            $price = $_POST['price'] ?? '';
            $stock = $_POST['stock_quantity'] ?? '';
            $img = $_FILES['image'] ?? null;
            $active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
            $description = trim($_POST['description'] ?? '');
            $sizes = $_POST['sizes'];

            // Kiểm tra dữ liệu đầu vào từng bước, nếu có lỗi thì dừng ngay
            if (empty($name)) {
                $_SESSION['errors'] = ["Tên sản phẩm không được để trống!"];
                header("Location: /admin/product");
                exit;
            }

            if (empty($category_id) || !is_numeric($category_id)) {
                $_SESSION['errors'] = ["Danh mục sản phẩm không hợp lệ!"];
                header("Location: /admin/product");
                exit;
            }

            if (empty($price) || !is_numeric($price) || $price < 0) {
                $_SESSION['errors'] = ["Đơn giá phải là số và không được âm!"];
                header("Location: /admin/product");
                exit;
            }

            if (empty($stock) || !is_numeric($stock) || floor($stock) != $stock || $stock < 0) {
                $_SESSION['errors'] = ["Số lượng phải là số nguyên không âm!"];
                header("Location: /admin/product");
                exit;
            }

            if (strlen($description) > 500) {
                $_SESSION['errors'] = ["Mô tả không được vượt quá 500 ký tự!"];
                header("Location: /admin/product");
                exit;
            }

            if (!in_array($active, [0, 1])) {
                $_SESSION['errors'] = ["Trạng thái không hợp lệ!"];
                header("Location: /admin/product");
                exit;
            }

            // Xử lý upload file
            $img_path = '';
            if (empty($img) || $img['error'] != UPLOAD_ERR_OK) {
                $_SESSION['errors'] = ["Vui lòng chọn file ảnh hợp lệ!"];
                header("Location: /admin/product");
                exit;
            } else {
                try {
                    $img_path = $this->uploadFile($img, 'products');
                } catch (\Exception $e) {
                    $_SESSION['errors'] = ["Lỗi khi upload file: " . $e->getMessage()];
                    header("Location: /admin/product");
                    exit;
                }
            }
            // Chuẩn bị dữ liệu để chèn
            $data = [
                "name" => $name,
                "category_id" => (int)$category_id,
                "price" => (float)$price,
                "stock_quantity" => (int)$stock,
                "image_url" => $img_path,
                "is_active" => $active,
                "description" => $description
            ];
            try {
                $idProduct = $this->product->insert($data);
                if ($idProduct > 0) {
                    $_SESSION['success'] = ["Thêm sản phẩm thành công! ID sản phẩm: $idProduct"];
                    foreach($sizes as $size) {
                        $data = [
                            "product_id" => $idProduct,
                            "size_name" => $size,
                            "stock_quantity" => (int)$stock / sizeof($sizes),
                        ];
                        $this->size->insert($data);
                    }
                } else {
                    $_SESSION['errors'] = ["Không thể thêm sản phẩm vào cơ sở dữ liệu!"];
                }
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Lỗi cơ sở dữ liệu: " . $e->getMessage()];
            }
            header("Location: /admin/product");
            exit;
    }


    public function store()
    {
        echo "Xử lý thêm dữ liệu.";
    }

    public function edit($id)
    {
        $name = trim($_POST['name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stock = $_POST['stock_quantity'] ?? '';
            $img = $_FILES['image'] ?? null;
            $active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
            $description = trim($_POST['description'] ?? '');
            $sizes = $_POST['sizes'];

            // Kiểm tra dữ liệu đầu vào từng bước, nếu có lỗi thì dừng ngay
            if (empty($name)) {
                $_SESSION['errors'] = ["Tên sản phẩm không được để trống!"];
                header("Location: /admin/product");
                exit;
            }
            if (empty($price) || !is_numeric($price) || $price < 0) {
                $_SESSION['errors'] = ["Đơn giá phải là số và không được âm!"];
                header("Location: /admin/product");
                exit;
            }

            if (empty($stock) || !is_numeric($stock) || floor($stock) != $stock || $stock < 0) {
                $_SESSION['errors'] = ["Số lượng phải là số nguyên không âm!"];
                header("Location: /admin/product");
                exit;
            }

            if (strlen($description) > 500) {
                $_SESSION['errors'] = ["Mô tả không được vượt quá 500 ký tự!"];
                header("Location: /admin/product");
                exit;
            }

            if (!in_array($active, [0, 1])) {
                $_SESSION['errors'] = ["Trạng thái không hợp lệ!"];
                header("Location: /admin/product");
                exit;
            }

            // Xử lý upload file
            $img_path = '';
            if (!empty($img) && $img['error'] == UPLOAD_ERR_OK) {
                try {
                    $img_path = $this->uploadFile($img, 'products');
                } catch (\Exception $e) {
                    $_SESSION['errors'] = ["Lỗi khi upload file: " . $e->getMessage()];
                    header("Location: /admin/product");
                    exit;
                }
            } else {
                $img_path = "";
            }
            // Chuẩn bị dữ liệu để chèn
            $data = [
                "name" => $name,
                "price" => (float)$price,
                "stock_quantity" => (int)$stock,
                "is_active" => $active,
                "description" => $description
            ];
            if($img_path) {
                $data["image_url"] = $img_path;

            }
            try {
                $idProduct = $this->product->update($id, $data);
                if ($idProduct >= 0) {
                    $_SESSION['success'] = ["Thêm sản phẩm thành công! ID sản phẩm: $idProduct"];
                    $deleteProduct = $this->size->deleteSize($id);
                    if ($deleteProduct >= 0) {
                        foreach($sizes as $size) {
                            $data = [
                                "product_id" => $idProduct,
                                "size_name" => $size,
                                "stock_quantity" => (int)$stock / sizeof($sizes),
                            ];
                            $this->size->insert($data);
                        }
                    } else {
                        $_SESSION["errors"] = ["Lỗi không thể chỉnh sửa size"];
                    }
                } else {
                    $_SESSION['errors'] = ["Không thể thêm sản phẩm vào cơ sở dữ liệu!"];
                }
            } catch (\Exception $e) {
                $_SESSION['errors'] = ["Lỗi cơ sở dữ liệu: " . $e->getMessage()];
            }
            header("Location: /admin/product");
            exit;
    }

    public function update($id)
    {
        echo "Cập nhật dữ liệu ID: $id";
    }

    public function delete($id)
    {
        try {
            $this->product->delete($id);
            $_SESSION["success"] = ["Đã xóa thành công sản phẩm"];
        } catch (\Exception $e) {
            $_SESSION["errors"] = ["Lỗi không thể xóa được sản phẩm" . $e->getMessage()];
        }
        header("Location: /admin/product");
        exit;
    }
}
