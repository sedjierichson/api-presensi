<?php

class Kantor{
    private $conn;
    private $table = 'kantor';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT * FROM kantor WHERE kantor.status <> 0 ORDER BY kantor.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function insertKantor($nama, $alamat) {
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?,1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nama, $alamat]);

        if ($stmt->rowCount() > 0) {
            $query_take = "SELECT * FROM `$this->table` ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query_take);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } else {
            return false;
        }
    }

    public function updateDetailKantor($id_kantor, $nama, $alamat){
        $query = "UPDATE `$this->table` SET nama = ?, alamat = ? WHERE id = ? AND status <> 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nama, $alamat,(int)$id_kantor]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function deactivateData($id) {
        $query = "UPDATE `$this->table` SET status = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}