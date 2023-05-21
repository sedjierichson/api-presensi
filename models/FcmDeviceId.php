<?php

class FcmDeviceId {
    private $conn;
    private $table = 'fcmToken';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getDataByIzin($id) {
        $query = "SELECT f.token AS keyClient FROM detail_izin d LEFT JOIN pegawai p ON d.nik_atasan = p.nik LEFT JOIN fcmToken f ON p.nik = f.nik WHERE d.id = ? AND f.token iS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
        // $stmt->execute([(int)$id]);
        // $row = [];
        // while($data = $stmt->fetch(PDO::FETCH_OBJ))
        //     $row[] = $data;

        // return $row;
    }

    public function insertData($nik, $idDevice) {

        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, $idDevice]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function removeData($nik, $idDevice) {
        $query = "DELETE FROM `$this->table` WHERE nik = ? AND token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, $idDevice]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
