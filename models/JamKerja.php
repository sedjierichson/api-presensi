<?php

class JamKerja{
    private $conn;
    private $table = 'jam_kerja';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT * FROM `$this->table` ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }
    public function getSingleData($id) {
        $query = "SELECT * FROM `$this->table` WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function updateJamKerja($id, $jamMasuk, $jamPulang) {
        $query = "UPDATE `$this->table` SET jam_masuk = ?, jam_pulang = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$jamMasuk, $jamPulang, (int)$id]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}