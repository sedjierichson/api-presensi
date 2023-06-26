<?php

class Presensi{
    private $conn;
    private $table = 'presensi_pegawai';

    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllData() {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.status <> 0 AND presensi_pegawai.is_history = 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getAllDataByIsHistory($ishistory) {
        // $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai2.jam_masuk, presensi_pegawai2.jam_keluar, presensi_pegawai.jam_masuk as jam_masuk_history, presensi_pegawai.jam_keluar as jam_keluar_history, TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar) as durasi, TIME_TO_SEC(TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar)) as detik, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        // FROM `presensi_pegawai` 
        // LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik 
        // LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor 
        // INNER JOIN presensi_pegawai as presensi_pegawai2
        // on presensi_pegawai2.tanggal = presensi_pegawai.tanggal
        // WHERE presensi_pegawai.is_history = $ishistory AND presensi_pegawai.status <> 0 AND presensi_pegawai2.is_history = 0
        // ORDER BY presensi_pegawai.tanggal DESC;";
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar,TIMEDIFF(presensi_pegawai.jam_keluar , presensi_pegawai.jam_masuk) as durasi, ABS(TIME_TO_SEC(TIMEDIFF(presensi_pegawai.jam_keluar , presensi_pegawai.jam_masuk))) as detik, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.nik, presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$ishistory]);

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }
    public function getHistoryByNIKTanggal($nik, $tanggal) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai2.jam_masuk, presensi_pegawai2.jam_keluar, presensi_pegawai.jam_masuk as jam_masuk_history, presensi_pegawai.jam_keluar as jam_keluar_history, TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar) as durasi, TIME_TO_SEC(TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar)) as detik, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor 
        INNER JOIN presensi_pegawai as presensi_pegawai2
        on presensi_pegawai2.tanggal = presensi_pegawai.tanggal
        WHERE presensi_pegawai.nik = $nik AND presensi_pegawai.tanggal = '$tanggal' AND presensi_pegawai.is_history = 1 AND presensi_pegawai.status <> 0 AND presensi_pegawai2.is_history = 0 ORDER BY presensi_pegawai.tanggal DESC;;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, $tanggal]);

        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getSingleData($id) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.id = ? AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getDataByNIKPegawai($nik) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.nik = ? AND presensi_pegawai.is_history = 0 AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getDataAbsenByNIKTanggal($nik, $tanggal) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar) as durasi, TIME_TO_SEC(TIMEDIFF(presensi_pegawai.jam_masuk , presensi_pegawai.jam_keluar)) as detik, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
        FROM `presensi_pegawai`
        LEFT JOIN pegawai ON pegawai.nik = presensi_pegawai.nik
        LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE presensi_pegawai.nik = ? AND presensi_pegawai.tanggal = ? AND presensi_pegawai.is_history = 0 AND presensi_pegawai.status <> 0 ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, $tanggal]);
        
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getDataByIdKantor($id_kantor) {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, pegawai.nama, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status 
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
    public function getPresensiByNikAtasan($nik_atasan, $bulan, $tahun) {
        // $query = "SELECT DISTINCT presensi_pegawai.nik, pegawai.nama, detail_izin.nik_atasan, presensi_pegawai.kategori, presensi_pegawai.tanggal 
        // FROM `presensi_pegawai`
        // left JOIN detail_izin on detail_izin.nik_pegawai=presensi_pegawai.nik 
        // LEFT JOIN pegawai on pegawai.nik = presensi_pegawai.nik
        // WHERE presensi_pegawai.is_history = 0 AND detail_izin.nik_atasan = ?;";
        $query = "SELECT nik, kategori, count(kategori) as jumlah
        FROM presensi_pegawai
        WHERE is_history = 0 and month(tanggal) = $bulan and year(tanggal) = $tahun
        GROUP BY kategori, nik
        ORDER BY nik, kategori;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik_atasan, $bulan, $tahun]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function getUserSudahAbsenMasuk() {
        $query = "SELECT p.id, p.nik, p.id_kantor, p.tanggal, p.jam_masuk, p.jam_keluar, p.foto, p.kategori, p.is_history, p.status, TIMEDIFF(p.jam_keluar , p.jam_masuk) as jam_kerja FROM presensi_pegawai p WHERE nik = ? AND tanggal = ? AND is_history = 0 ORDER BY id DESC LIMIT 1;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->nik, $this->tanggal]);
        // return $this->nik;
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
    public function getUserSudahAbsenLengkap() {
        $query = "SELECT * FROM presensi_pegawai WHERE nik = ? AND tanggal = ? AND jam_masuk <> 0 and jam_keluar <> 0 AND is_history = 0 ORDER BY id DESC LIMIT 1;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->nik, $this->tanggal]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
    public function getDataByNIKPegawaidanFilterTahunBulan() {
        $query = "SELECT presensi_pegawai.id, presensi_pegawai.nik, presensi_pegawai.id_kantor, kantor.nama as lokasi, presensi_pegawai.tanggal, presensi_pegawai.jam_masuk, presensi_pegawai.jam_keluar, presensi_pegawai.foto, presensi_pegawai.kategori, presensi_pegawai.is_history, presensi_pegawai.status FROM `presensi_pegawai` LEFT JOIN kantor on kantor.id = presensi_pegawai.id_kantor WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ? AND nik = ? ORDER BY presensi_pegawai.tanggal DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->tahun, $this->bulan, $this->nikk]);
        $row = [];
        while($data = $stmt->fetch(PDO::FETCH_OBJ))
            $row[] = $data;

        return $row;
    }

    public function insertPresensiMasukPegawai($nik, $id_kantor, $tanggal, $jam_masuk, $url, $kategori, $is_history){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?, ?, NULL, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, (int)$id_kantor, $tanggal, $jam_masuk, $url, $kategori, (int)$is_history]);

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

    public function insertPresensiMasukPegawaiByAdmin($nik, $id_kantor, $tanggal, $jam_masuk, $jam_keluar, $url, $kategori, $is_history){
        $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([(int)$nik, (int)$id_kantor, $tanggal, $jam_masuk, $jam_keluar,$url, $kategori, (int)$is_history]);

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

    public function updatePresensiKeluar($id, $jam_keluar, $nik){
        $query = "UPDATE `$this->table` SET jam_keluar = ? WHERE id = ? AND status <> 0; DELETE FROM `$this->table` WHERE nik = ? and jam_masuk IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$jam_keluar,(int)$id, (int)$nik]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertHistoryPresensiKeluar($nik, $id_kantor, $tanggal, $jam_keluar, $url, $kategori, $is_history){
        $query = "SELECT * FROM `$this->table` WHERE nik = ? AND tanggal = ? AND jam_masuk IS NULL AND is_history = 1 AND status = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nik, $tanggal]);
        if ($stmt->rowCount() > 0) {
            return -1;
        } else {
            $query = "INSERT INTO `$this->table` VALUES (DEFAULT, ?, ?, ?, NULL, ?, ?, ?, ?, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([(int)$nik, (int)$id_kantor, $tanggal, $jam_keluar, $url, $kategori, (int)$is_history]);

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

    public function updateHistoryPresensiKembali($nik, $tanggal, $jam_kembali){
        $query = "UPDATE `$this->table` SET jam_masuk = ? WHERE nik = ? AND tanggal = ? AND jam_masuk IS NULL AND is_history = 1 AND status <> 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$jam_kembali, (int)$nik, $tanggal]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateKategoriPresensi($id, $kategori){
        $query = "UPDATE `$this->table` SET kategori = ? WHERE id = ? AND status <> 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$kategori,(int)$id]);
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