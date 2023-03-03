<?php

class DetailIzin{
    private $conn;
    private $table = 'detail_izin';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllData() {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, detail_izin.tempat_tujuan, detail_izin.uraian_tugas FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin WHERE detail_izin.status <> 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getSingleData($id) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, detail_izin.tempat_tujuan, detail_izin.uraian_tugas FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin WHERE detail_izin.id = ? AND detail_izin.status <> 0;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getDataByNIKPegawai($nik_pegawai) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, detail_izin.tempat_tujuan, detail_izin.uraian_tugas FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin WHERE detail_izin.nik_pegawai = ? AND detail_izin.status <> 0;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

}