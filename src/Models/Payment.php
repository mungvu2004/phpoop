<?php

namespace App\Models;

use App\Model;

class Payment extends Model
{
    protected $tableName = 'paymenthistory';

    public function getAllPay() {
        $query = $this->conn->createQueryBuilder();
        $query->select('p.*', 'ud.recipient_name')
        ->from($this->tableName, 'p')
        ->leftJoin('p', 'user_addresses', 'ud', 'p.user_id = ud.user_id');

    return $query->fetchAllAssociative();
    }
}