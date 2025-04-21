<?php
namespace App\Controllers\Client;

use App\Controller;
use App\Models\Coupon;

/**
 * Lớp CouponController quản lý các thao tác liên quan đến mã giảm giá
 * 
 * Lớp này kế thừa từ Controller cơ sở và cung cấp các phương thức đặc thù
 * cho việc xử lý các yêu cầu liên quan đến mã giảm giá từ phía người dùng.
 */
class CouponController {
    private Coupon $coupon;

    public function __construct() {
        $this->coupon = new Coupon();
    }

    public function check($code) {
        $coupon = strtolower($code);
        $coupon = $this->coupon->countCoupon($coupon);
        if ($coupon > 0) {
            return true;
        } else {
            return false;
        }
    }
}