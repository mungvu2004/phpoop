<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Coupon quản lý các thao tác liên quan đến mã giảm giá
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin mã giảm giá trong hệ thống.
 */
class Coupon extends Model
{
    /**
     * @var string Tên bảng coupons trong cơ sở dữ liệu
     */
    protected $tableName = 'coupons';

    /**
     * Đếm số lượng mã giảm giá theo mã code
     * 
     * @param string $code Mã code của coupon
     * @return int Số lượng coupon có mã code tương ứng
     */
    public function countCoupon($code) {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*)')
        ->from($this->tableName)
        ->where('code = :code')
        ->setParameter('code', $code);

        return $query->fetchOne();
    }
}