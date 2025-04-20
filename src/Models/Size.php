<?php
namespace App\Models;

use App\Model;

/**
 * Lớp Size quản lý các thao tác liên quan đến kích thước sản phẩm
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin kích thước sản phẩm trong hệ thống.
 */
class Size extends Model 
{
    /**
     * @var string Tên bảng size trong cơ sở dữ liệu
     */
    protected $tableName = "size";

    /**
     * Lấy danh sách tất cả kích thước của sản phẩm
     * 
     * @param int $id ID của sản phẩm
     * @return array Danh sách kích thước của sản phẩm
     */
    public function selectAll($id)  {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }

    /**
     * Xóa tất cả kích thước của sản phẩm
     * 
     * @param int $id ID của sản phẩm
     * @return int Số bản ghi bị xóa
     */
    public function deleteSize($id) {
        $query = $this->conn->createQueryBuilder();
        $query->delete($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->executeStatement();
    }
}