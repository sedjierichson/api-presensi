<?php

class Beacon{
    private $conn;
    private $table = 'beacon';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllData() {
        $query = "SELECT beacon.id as id_beacon, beacon.id_kantor, k.nama as lokasi_kantor, beacon.uuid, beacon.nama, beacon.lokasi, beacon.status FROM beacon LEFT JOIN kantor k on k.id = beacon.id_kantor WHERE beacon.status <> 0;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getSingleData($id) {
        $query = "SELECT beacon.id as id_beacon, beacon.id_kantor, k.nama as lokasi_kantor, beacon.uuid, beacon.nama, beacon.lokasi, beacon.status FROM beacon LEFT JOIN kantor k on k.id = beacon.id_kantor WHERE beacon.id = ? AND beacon.status <> 0;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function insertBeacon($id_kantor, $uuid, $nama, $lokasi){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id_kantor, $uuid, $nama, $lokasi]);

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