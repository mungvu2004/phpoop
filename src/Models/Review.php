<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Review quản lý các thao tác liên quan đến đánh giá sản phẩm
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin đánh giá sản phẩm trong hệ thống.
 */
class Review extends Model
{
    /**
     * @var string Tên bảng reviews trong cơ sở dữ liệu
     */
    protected $tableName = 'reviews';

    /**
     * Lấy danh sách đánh giá của sản phẩm
     * 
     * @param int $id ID của sản phẩm
     * @return array Danh sách đánh giá của sản phẩm
     */
    public function review($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }
}