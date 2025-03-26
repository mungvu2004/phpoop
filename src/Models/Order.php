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
            ->leftJoin('o', 'coupons', 'c', 'o.coupon_id = c.id')  
            ->orderBy('o.created_at', 'DESC');  
    
        return $query->fetchAllAssociative();
    }
    
    
}