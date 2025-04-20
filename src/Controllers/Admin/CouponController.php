<?php
namespace App\Controllers\Admin;

use App\Models\Coupon;
use App\Controller;

/**
 * Lớp CouponController quản lý các thao tác liên quan đến mã giảm giá
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý mã giảm giá như thêm, sửa, xóa và cập nhật trạng thái.
 */
class CouponController extends Controller
{
    /**
     * @var Coupon Model xử lý dữ liệu mã giảm giá
     */
    private Coupon $coupon;

    /**
     * Khởi tạo controller và model Coupon
     */
    public function __construct()
    {
        $this->coupon = new Coupon();
    }

    /**
     * Hiển thị danh sách mã giảm giá
     * 
     * @return mixed View hiển thị danh sách mã giảm giá
     */
    public function index() {
        $coupons = $this->coupon->findALL();
        return view("admin.coupon", compact("coupons"));
    }

    /**
     * Xử lý thêm mã giảm giá mới
     * 
     * @return void Chuyển hướng về trang danh sách mã giảm giá
     */
    public function create() {
        try {
            // Khởi tạo mảng lỗi
            $errors = [];
        
            // Kiểm tra và lấy dữ liệu từ $_POST
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : null;
            $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
            $is_percentage = 1; // Cố định theo phần trăm
            $is_active = isset($_POST['is_active']) ? 1 : 0; // Checkbox: 1 nếu chọn, 0 nếu không
        
            // Kiểm tra mã giảm giá
            if (empty($code)) {
                $errors[] = "Mã giảm giá không được để trống!";
            } elseif (strlen($code) > 50) {
                $errors[] = "Mã giảm giá không được dài quá 50 ký tự!";
            }
        
            // Kiểm tra phần trăm giảm giá
            if ($discount === null || $discount < 0 || $discount > 100) {
                $errors[] = "Phần trăm giảm phải từ 0 đến 100!";
            }
        
            // Kiểm tra ngày hết hạn
            if (empty($expiry_date)) {
                $errors[] = "Ngày hết hạn không được để trống!";
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiry_date)) {
                $errors[] = "Ngày hết hạn không đúng định dạng (YYYY-MM-DD)!";
            } elseif (strtotime($expiry_date) < strtotime(date('Y-m-d'))) {
                $errors[] = "Ngày hết hạn không được nhỏ hơn ngày hiện tại!";
            }
        
            // Kiểm tra mã giảm giá trùng lặp
            $errors = $this->coupon->countCoupon($code);
        
            // Nếu có lỗi, lưu vào session và chuyển hướng
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /admin/coupon");
                exit;
            }
            $data = [
                "code"=> $code,
                "discount" => $discount,
                "expiry_date"=> $expiry_date,
                "is_percentage"=> $is_percentage,
                "is_active"=> $is_active
            ];
            // Lưu dữ liệu vào cơ sở dữ liệu
            $this->coupon->insert($data);
        
            // Lưu thông báo thành công
            $_SESSION['success'] = ["Thêm mã giảm giá thành công!"];
            header("Location: /admin/coupon"); // Thay bằng trang danh sách phù hợp
            exit;
        
        } catch (\Exception $e) {
            $_SESSION['errors'] = ["Lỗi khi thêm mã giảm giá: " . $e->getMessage()];
            header("Location: /admin/coupon");
            exit;
        }
    }

    /**
     * Cập nhật trạng thái của mã giảm giá
     * 
     * @param int $id ID của mã giảm giá cần cập nhật
     * @return void Chuyển hướng về trang danh sách mã giảm giá
     */
    public function edit($id) {
        try {
            $is_active = $_POST['is_active'] ?? null;
    
            $this->coupon->update($id, ['is_active' => $is_active]);
    
            $_SESSION['success'] = ['Cập nhật trạng thái thành công!'];
        } catch (\Exception $e) {
            $_SESSION['error'] = ['Lỗi không tắt được mã giảm giá!!!'];
        }
    
        header('Location: /admin/coupon');
        exit;
    }
}