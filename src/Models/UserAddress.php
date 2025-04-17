<?php

namespace App\Models;

use App\Model;

class UserAddress extends Model {
    protected $tableName = "user_addresses";

    public function countAddress($id) {
        $query = $this->conn->createQueryBuilder();
        $query->select("COUNT(*)")
            ->from($this->tableName)
            ->where("user_id = :id")
            ->setParameter("id", $id);
        return $query->fetchOne();
    }
    public function updateAddress($id, array $data) {
        return $this->conn->update($this->tableName, $data, ["user_id"=> $id]);
    }
}