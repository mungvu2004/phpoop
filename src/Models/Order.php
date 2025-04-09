<?php

namespace App\Models;

use App\Model;

class Order extends Model
{
    protected $tableName = 'orders';

    public function dataMonth($month = null, $year = null) 
    {
        $month = $month ?? date('n');
        $year = $year ?? date('Y');
        $query = $this->conn->createQueryBuilder();
        $query->select('DAY(created_at) AS day, total_price')
            ->from($this->tableName)
            ->where('MONTH(created_at) = :month')
            ->andWhere('YEAR(created_at) = :year')
            ->andWhere('status = :status')
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('status', 'completed')
            ->orderBy('DAY(created_at)', 'ASC');

        return $query->fetchAllAssociative();
    }
    public function getAll() {
        $query = $this->conn->createQueryBuilder();
        $query->select('o.*, u.username, c.code AS coupon_code')  
            ->from($this->tableName, 'o')  
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id')  
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id') ; 
        return $query->fetchAllAssociative();
    }
    
    public function paginateOrder($page = 1, $limit = 8) {
        $offset = ($page -1) * $limit;
        $query = $this->conn->createQueryBuilder();
        $query->select('o.*, u.username, c.code AS coupon_code')  
            ->from($this->tableName, 'o')  
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id')  
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id')  
            ->setFirstResult($offset)
            ->setMaxResults($limit);
    
        $data =  $query->fetchAllAssociative();
        $totalItem = $this->count();
        $totalPage = ceil($this->count() / $limit);

        return [
            'data' => $data,
            'page' => $page,
            'limit' => $limit,
            'totalItem'=> $totalItem,
            'totalPage' => $totalPage,
        ];
    }
    public function detailOrder($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            // Từ bảng orders (o)
            'o.id AS order_id',
            'o.user_id',
            'o.total_price',
            'o.status',
            'o.shipping_address',
            'o.shipping_method',
            'o.shipping_fee',
            
            // Từ bảng orderdetails (od)
            'od.quantity',
            'od.subtotal',
            
            // Từ bảng products (p)
            'p.name AS product_name',
            'p.price',
            'p.image_url',
            
            // Từ bảng users (u) - loại bỏ password_hash
            'u.username',
            
            // Từ bảng paymenthistory (ph)
            'ph.payment_method'
        )
            ->from($this->tableName, 'o') // Bảng orders
            ->leftJoin('o', 'orderdetails', 'od', 'o.id = od.order_id') // Liên kết với orderdetails
            ->leftJoin('od', 'products', 'p', 'od.product_id = p.id') // Liên kết với products
            ->leftJoin('o', 'users', 'u', 'o.user_id = u.id') // Liên kết với users
            ->leftJoin('o', 'paymenthistory', 'ph', 'o.id = ph.order_id') // Liên kết với paymenthistory
            ->where('o.id = :id')
            ->setParameter('id', $id);
        
        return $query->fetchAllAssociative();
    }
}