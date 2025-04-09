<?php

namespace App\Models;

use App\Model;

class User extends Model
{
    protected $tableName = 'users';
    
    public function getAllUser() {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            'u.id',
            'u.username',
            'u.email',
            'u.role',
            'u.is_active',
            'ud.recipient_name'
        )
            ->from($this->tableName, 'u')
            ->leftJoin('u', 'user_addresses', 'ud', 'u.id = ud.user_id');
        return $query->fetchAllAssociative();
    }

    public function detailUser($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select(
            'u.username',
            'u.email',
            'u.role',
            'u.is_active',
            'ud.*',
        )
            ->from($this->tableName, 'u')
            ->leftJoin('u', 'user_addresses', 'ud', 'u.id = ud.user_id')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $query->fetchAllAssociative();
    }
}