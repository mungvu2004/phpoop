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
    public function getCouponId($code) {
        $query = $this->conn->createQueryBuilder();
        $query->select('id')  // Chọn trường id
            ->from($this->tableName)
            ->where('code = :code')
            ->setParameter('code', $code);
    
        $result = $query->fetchOne();
    
        return $result ? $result : null;
    }

    /**
     * Lấy thông tin chi tiết về mã giảm giá
     * 
     * @param string $code Mã code của coupon
     * @return array|null Thông tin chi tiết về coupon hoặc null nếu không tìm thấy
     */
    public function getCouponDetails($code) {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('code = :code')
            ->andWhere('is_active = 1')
            ->andWhere('expiry_date >= CURRENT_DATE()')
            ->setParameter('code', $code);
    
        $result = $query->fetchAssociative();
    
        if ($result) {
            return [
                'id' => $result['id'],
                'discount' => $result['discount'],
                'discount_type' => $result['is_percentage'] ? 'percentage' : 'fixed'
            ];
        }
    
        return null;
    }
}