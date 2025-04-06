<?php

namespace App\Models;

use App\Model;

class Review extends Model
{
    protected $tableName = 'reviews';
    public function review($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select('*')
            ->from($this->tableName)
            ->where('product_id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }
}