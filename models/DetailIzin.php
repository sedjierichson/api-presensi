<?php

class DetailIzin{
    private $conn;
    private $table = 'detail_izin';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllData() {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, k.nama as tempat_tujuan, detail_izin.uraian_tugas, detail_izin.tanggal_pengajuan, detail_izin.tanggal_respon, detail_izin.status FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin LEFT JOIN kantor k on k.id = detail_izin.id_kantor_tujuan WHERE detail_izin.status <> 0 ORDER BY detail_izin.tanggal_pengajuan DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getSingleData($id) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, k.nama as tempat_tujuan, detail_izin.uraian_tugas, detail_izin.tanggal_pengajuan, detail_izin.tanggal_respon, detail_izin.status FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin LEFT JOIN kantor k on k.id = detail_izin.id_kantor_tujuan WHERE detail_izin.id = ? AND detail_izin.status <> 0 ORDER BY detail_izin.tanggal_pengajuan DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getDataByNIKPegawai($nik_pegawai) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, k.nama as tempat_tujuan, detail_izin.uraian_tugas, detail_izin.tanggal_pengajuan, detail_izin.tanggal_respon, detail_izin.status FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin LEFT JOIN kantor k on k.id = detail_izin.id_kantor_tujuan WHERE detail_izin.nik_pegawai = ? AND detail_izin.status <> 0 ORDER BY detail_izin.tanggal_pengajuan DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }
    public function getDataByNIKAtasan($nik_atasan) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, k.nama as tempat_tujuan, detail_izin.uraian_tugas, detail_izin.tanggal_pengajuan, detail_izin.tanggal_respon, detail_izin.status FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin LEFT JOIN kantor k on k.id = detail_izin.id_kantor_tujuan WHERE detail_izin.nik_atasan = ? AND detail_izin.status <> 0 ORDER BY detail_izin.tanggal_pengajuan DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_atasan]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getDataByJenisIzin($id_izin) {
        $query = "SELECT detail_izin.id, detail_izin.id_jenis_izin, j.nama_izin as tipe_izin, detail_izin.nik_pegawai,detail_izin.nik_atasan, detail_izin.tanggal_awal, detail_izin.tanggal_akhir, detail_izin.jam_awal, detail_izin.jam_akhir, detail_izin.alasan, k.nama as tempat_tujuan, detail_izin.uraian_tugas, detail_izin.tanggal_pengajuan, detail_izin.tanggal_respon, detail_izin.status FROM detail_izin LEFT JOIN jenis_izin j ON j.id = detail_izin.id_jenis_izin LEFT JOIN kantor k on k.id = detail_izin.id_kantor_tujuan WHERE detail_izin.id_jenis_izin = ? AND detail_izin.status <> 0 ORDER BY detail_izin.tanggal_pengajuan DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id_izin]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function insertIzinPulangCepat($nik_pegawai, $nik_atasan, $tanggal_izin, $jam_izin_pulang, $alasan, $tanggal_pengajuan){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, 1, ?, ?, ?, NULL, ?, NULL, ?, NULL, NULL, ?, NULL,1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai, (int)$nik_atasan, $tanggal_izin, $jam_izin_pulang, $alasan , $tanggal_pengajuan]);

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

    public function insertIzinMeninggalkanKantor($nik_pegawai, $nik_atasan, $tanggal_izin, $jam_awal, $jam_akhir, $alasan, $tanggal_pengajuan){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, 2, ?, ?, ?, NULL, ?, ?, ?, NULL, NULL, ?, NULL,1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai, (int)$nik_atasan, $tanggal_izin, $jam_awal, $jam_akhir, $alasan, $tanggal_pengajuan]);

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
    public function insertIzinSuratTugas($nik_pegawai, $nik_atasan, $tanggal_awal, $tanggal_akhir, $uraian_tugas, $tempat_tujuan, $tanggal_pengajuan){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, 3, ?, ?, ?, ?, NULL, NULL, NULL, ?, ?, ?, NULL, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai, (int)$nik_atasan, $tanggal_awal, $tanggal_akhir, $uraian_tugas, (int)$tempat_tujuan, $tanggal_pengajuan]);

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
    public function insertIzinLupaAbsen($nik_pegawai, $nik_atasan, $tanggal_lupa_absen, $jam_awal, $jam_akhir, $alasan, $tanggal_pengajuan){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, 4, ?, ?, ?, NULL, ?, ?, ?, NULL, NULL, ?, NULL, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_pegawai, (int)$nik_atasan, $tanggal_lupa_absen, $jam_awal, $jam_akhir, $alasan, $tanggal_pengajuan]);

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