<?php

namespace App\Models;

use App\Model;

/**
 * Lớp Product quản lý các thao tác liên quan đến sản phẩm
 * 
 * Lớp này kế thừa từ Model cơ sở và cung cấp các phương thức đặc thù
 * cho việc quản lý thông tin sản phẩm trong hệ thống.
 */
class Product extends Model
{
    /**
     * @var string Tên bảng products trong cơ sở dữ liệu
     */
    protected $tableName = 'products';

    /**
     * Lấy danh sách 4 sản phẩm mới nhất với đánh giá trung bình
     * 
     * @return array Danh sách sản phẩm với các thông tin: id, name, category_id, price, 
     * stock_quantity, image_url, description, is_active, average_rating
     */
    public function getAll()
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from($this->tableName, 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->where('p.is_active = 1')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            )
            ->orderBy('p.id', 'DESC') // hoặc 'p.created_at' nếu có
            ->setMaxResults(4);

        return $qb->fetchAllAssociative();
    }

    /**
     * Lấy danh sách 4 sản phẩm có đánh giá cao nhất
     * 
     * @return array Danh sách sản phẩm với các thông tin: id, name, category_id, price, 
     * stock_quantity, image_url, description, is_active, average_rating
     */
    public function rating(): mixed
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from($this->tableName, 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->where('p.is_active = 1')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            )
            ->orderBy('average_rating', 'DESC')
            ->setMaxResults(4);
        return $qb->fetchAllAssociative();
    }

    /**
     * Lấy danh sách tất cả sản phẩm với đánh giá trung bình
     * 
     * @return array Danh sách sản phẩm với các thông tin: id, name, category_id, price, 
     * stock_quantity, image_url, description, is_active, average_rating
     */
    public function listProduct(): mixed
    {
        $qb = $this->conn->createQueryBuilder();

        $qb->select(
            'p.id',
            'p.name',
            'p.category_id',
            'p.price',
            'p.stock_quantity',
            'p.image_url',
            'p.description',
            'p.is_active',
            'AVG(r.rating) AS average_rating'
        )
            ->from('products', 'p')
            ->leftJoin('p', 'reviews', 'r', 'p.id = r.product_id')
            ->groupBy(
                'p.id',
                'p.name',
                'p.category_id',
                'p.price',
                'p.stock_quantity',
                'p.image_url',
                'p.description',
                'p.is_active'
            );
        return $qb->fetchAllAssociative();
    }
}
