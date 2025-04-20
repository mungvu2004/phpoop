<?php

namespace App\Models;

use App\Model;

/**
 * Lớp OrderDetail quản lý các thao tác liên quan đến chi tiết đơn hàng
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin chi tiết đơn hàng trong hệ thống.
 */
class OrderDetail extends Model
{
    /**
     * @var string Tên bảng orderdetails trong cơ sở dữ liệu
     */
    protected $tableName = 'orderdetails';
    
    /**
     * Tìm chi tiết đơn hàng theo order_id, product_id và size_id
     * 
     * @param int $orderId ID của đơn hàng
     * @param int $productId ID của sản phẩm
     * @param int $sizeId ID của size sản phẩm
     * @return array|null Chi tiết đơn hàng nếu tìm thấy, null nếu không tìm thấy
     */
    public function findByOrderAndProduct($orderId, $productId, $sizeId)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('order_id = :orderId')
            ->andWhere('product_id = :productId')
            ->andWhere('size_id = :sizeId')
            ->setParameter('orderId', $orderId)
            ->setParameter('productId', $productId)
            ->setParameter('sizeId', $sizeId);
        
        return $query->fetchAssociative();
    }

    /**
     * Kiểm tra xem sản phẩm đã tồn tại trong đơn hàng chưa
     * 
     * @param int $orderId ID của đơn hàng
     * @param int $productId ID của sản phẩm
     * @param int $sizeId ID của size sản phẩm
     * @return bool True nếu sản phẩm đã tồn tại, false nếu chưa
     */
    public function checkInsert($orderId, $productId, $sizeId)
    {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*) as count')
            ->from($this->tableName)
            ->where('order_id = :order_id')
            ->andWhere('product_id = :product_id')
            ->andWhere('size_id = :size_id')
            ->setParameter('order_id', $orderId)
            ->setParameter('product_id', $productId)
            ->setParameter('size_id', $sizeId);
        
        $result = $query->executeQuery()->fetchAssociative();
        return $result['count'] > 0;
    }
}