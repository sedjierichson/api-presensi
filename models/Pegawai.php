<?php

class Pegawai{
    private $conn;
    private $table = 'pegawai';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT * FROM `$this->table` ORDER BY pegawai.nik";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getCurrentUserData() {
        $query = "SELECT * FROM `$this->table` WHERE (nik = ?) LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->nik]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insertUserBaru($nik, $security_code) {
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, NULL, NULL, ?, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nik, $security_code]);

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

    public function updatePinLogin($nik, $security_code){
        $query = "UPDATE `$this->table` SET security_code = ? WHERE nik = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$security_code, (int)$nik]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}