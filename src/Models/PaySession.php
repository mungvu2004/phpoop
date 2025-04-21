<?php

namespace App\Models;

use App\Model;

class PaySession extends Model 
{
    protected $tableName = 'paymentsessions';

    public function create($data) 
    {
        $query = $this->conn->createQueryBuilder();
        $query->insert($this->tableName)
            ->values([
                'order_id' => ':order_id',
                'session_id' => ':session_id',
                'status' => ':status'
            ])
            ->setParameter('order_id', $data['order_id'])
            ->setParameter('session_id', $data['session_id'] ?? uniqid('COD_', true))
            ->setParameter('status', $data['status'] ?? 'created');

        
        return $query->fetchAllAssociative();
    }

}
