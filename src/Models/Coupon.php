<?php

namespace App\Models;

use App\Model;

class Coupon extends Model
{
    protected $tableName = 'coupons';
    public function countCoupon($code) {
        $query = $this->conn->createQueryBuilder();
        $query->select('COUNT(*)')
        ->from($this->tableName)
        ->where('code = :code')
        ->setParameter('code', $code);

        return $query->fetchOne();
    }
}