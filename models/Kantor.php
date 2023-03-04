<?php

class DetailIzin{
    private $conn;
    private $table = 'detail_izin';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT * FROM kantor ORDER BY kantor.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }
}