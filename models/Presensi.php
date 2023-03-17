<?php

class Presensi{
    private $conn;
    private $table = 'presensi_pegawai';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }
    public function getSingleData($id) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.id = ? AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getDataByNIKPegawai($nik) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.nik = ? AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getDataByIdKantor($id_kantor) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.id_kantor = ? AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id_kantor]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getUserSudahAbsenMasuk() {
        $query = "SELECT * FROM presensi_pegawai WHERE nik = ? AND tanggal = ? AND jam_masuk <> 0 LIMIT 1;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->nik, $this->tanggal]);
        // return $this->nik;

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
                    return [
                        'status' => 1,
                        'message' => (int)$row['id']
                    ];
              
        } else {
            return [
                'status' => 0,
                'message' => 'Data Anda tidak ditemukan'
            ];
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertPresensiMasukPegawai($nik, $id_kantor, $tanggal, $jam_masuk, $foto){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?, ?, NULL, ?, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, (int)$id_kantor, $tanggal, $jam_masuk, $foto]);

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

    public function updatePresensiKeluar($id, $jam_keluar){
        $query = "UPDATE `$this->table` SET jam_keluar = ? WHERE id = ? AND status <> 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$jam_keluar,(int)$id]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}