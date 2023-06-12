<?php

class Log{
    private $conn;
    private $table = 'log_beacon';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllData() {
        $query = "SELECT * FROM `$this->table`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function insertLog($jarak, $jam, $keterangan) {
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$jarak, $jam, $keterangan]);

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
}