<?php
date_default_timezone_set("Asia/Bangkok");
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Presensi.php";
require "upload.php";

$database = new Database();
$db = $database->connect();

$presensi = new Presensi($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $res = $presensi->getSingleData($_GET['id']);
    } 
    else if (isset($_GET['is_history'])) {
        $res = $presensi->getAllDataByIsHistory($_GET['is_history']);
    }
    else if (isset($_GET['nik'])) {
        $res = $presensi->getDataByNIKPegawai($_GET['nik']);
    }
    else if (isset($_GET['nik_atasan']) && isset($_GET['bulan_rekap']) && isset($_GET['tahun_rekap'])) {
        $res = $presensi->getPresensiByNikAtasan($_GET['nik_atasan'],$_GET['bulan_rekap'],$_GET['tahun_rekap']);
    }
    else if (isset($_GET['nik_history']) && isset($_GET['tanggal_history'])) {
        $res = $presensi->getHistoryByNIKTanggal($_GET['nik_history'], $_GET['tanggal_history']);
        // $res2 = $presensi->getDataAbsenByNIKTanggal($_GET['nik_history'], $_GET['tanggal_history']);
    }
    else if (isset($_GET['id_kantor'])) {
        $res = $presensi->getDataByIdKantor($_GET['id_kantor']);
    } 
    else if (isset($_GET['nik_pegawai']) && isset($_GET['tanggal_absen'])) {
        $presensi->nik = (isset($_GET['nik_pegawai'])) ? $_GET['nik_pegawai'] : null;
        $presensi->tanggal = (isset($_GET['tanggal_absen'])) ? $_GET['tanggal_absen'] : null;
        $res = $presensi->getUserSudahAbsenMasuk();

    }
    else if (isset($_GET['nikk']) && isset($_GET['tahun']) && isset($_GET['bulan'])) {
        $presensi->nikk = (isset($_GET['nikk'])) ? $_GET['nikk'] : null;
        $presensi->tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : null;
        $presensi->bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : null;
        $res = $presensi -> getDataByNIKPegawaidanFilterTahunBulan();
    }
     else {
        $res = $presensi->getAllData();
    }

    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res,
            // 'data2'=>$res2
        );
    }
    else {
        $result = array(
            'status' => 0,
            'message' => 'Data presensi tidak ditemukan'
        );
    }
    echo json_encode($result);

} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $result = array(
        'status' => 0,
        'message' => 'Test 1234'
    );

    if(isset($_POST['nik']) && isset($_POST['id_kantor']) && isset($_POST['tanggal']) && isset($_POST['jam_masuk']) && isset($_POST['image']) && isset($_POST['img_name']) && isset($_POST['kategori']) && isset($_POST['is_history'])){
        // $uploadResult = uploadFile($_FILES, 'foto', 'files/');

        $image = $_POST['image'];
        $name = $_POST['img_name'];
        $realImage = base64_decode($image);

        file_put_contents('files/'.$name, $realImage);
        
        // if ($uploadResult['status'] != 1) {
        //     $result['status'] = 0;
        //     $result['message'] = $uploadResult['message'];
            
        // } else {
        if ($_POST['is_history'] == 1){
            $tmp = $presensi->insertHistoryPresensiKeluar($_POST['nik'], $_POST['id_kantor'], $_POST['tanggal'], date("H:i:s"), 'http://127.0.0.1:8888/api-presensi/api-presensi/api/files/'.$_POST['img_name'],  $_POST['kategori'], $_POST['is_history']);
            $tmp2 = $presensi->insertHistoryPresensiKeluar2($_POST['nik'],$_POST['tanggal'], $_POST['jam_masuk'], 0);
        } else {
            $tmp = $presensi->insertPresensiMasukPegawai($_POST['nik'], $_POST['id_kantor'], $_POST['tanggal'], date("H:i:s"), 'http://127.0.0.1:8888/api-presensi/api-presensi/api/files/'.$_POST['img_name'],  $_POST['kategori'], $_POST['is_history']);
            // $tmp2 = $presensi->insertHistoryPresensiKeluar2($_POST['nik'], $_POST['tanggal'], date("H:i:s"), 1);
            $tmp2 = $presensi->insertHistoryPresensiKeluar2($_POST['nik'], $_POST['tanggal'], $_POST['jam_masuk'], 1);
        }

        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;
        }
        // }
    } else if(isset($_POST['nik']) && isset($_POST['id_kantor']) && isset($_POST['tanggal']) && isset($_POST['jam_masuk']) && isset($_POST['jam_keluar']) && isset($_POST['image']) && isset($_POST['img_name']) && isset($_POST['kategori']) && isset($_POST['is_history'])){
        $image = $_POST['image'];
        $name = $_POST['img_name'];
        $realImage = base64_decode($image);

        file_put_contents('files/'.$name, $realImage);

        $tmp = $presensi->insertPresensiMasukPegawaiByAdmin($_POST['nik'], $_POST['id_kantor'], $_POST['tanggal'], $_POST['jam_masuk'],$_POST['jam_keluar'], 'http://127.0.0.1:8888/api-presensi/api-presensi/api/files/'.$_POST['img_name'],  $_POST['kategori'], $_POST['is_history']);
        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;
        }
    }
    else {
        $result['status'] = 0;
        $result['message'] = "Pastikan semua parameter sudah lengkap";
    }

    echo json_encode($result);
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_presensi) && isset($data->jam_keluar) && isset($data->jam_keluar)){
        $tmp = $presensi->updatePresensiKeluar($data->id_presensi, date("H:i:s"), $data->nik);
        $tmp2 = $presensi->updateHistoryPresensiKeluar($data->nik, date("Y-m-d"), date("H:i:s"),1);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Jam keluar berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Jam keluar gagal diupdate";
        }
    } else if (isset($data->id_presensi) && isset($data->kategori)){
        $tmp = $presensi->updateKategoriPresensi($data->id_presensi, $data->kategori);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Kategori berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Kategori gagal diupdate";
        }
    } else if (isset($data->jam_kembali) && isset($data->nik) && isset($data->tanggal)){
        // $tmp = $presensi -> updateHistoryPresensiKembali($data->nik, $data->tanggal, date("H:i:s"));
        // $tmp = $presensi -> updateHistoryPresensiKeluar($data->nik, $data->tanggal, date("H:i:s"), 0);
        $tmp = $presensi -> updateHistoryPresensiKeluar($data->nik, $data->tanggal, date("H:i:s"), 0);
        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Jam kembali berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Jam kembali gagal diupdate";
        }
    }

    else {
        $result['status'] = 0;
        $result['message'] = "Pastikan parameter sudah terisi";
    }

    echo json_encode($result);
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $result = array(
        'status' => 0,
        'message' => ''
    );
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id)) {
        $tmp = $presensi->deactivateData($data->id);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Data berhasil dihapus";
        } else {
            $result['status'] = 0;
            $result['message'] = "Data gagal dihapus";
        }
    } else {
        $result['status'] = 0;
        $result['message'] = "Pastikan parameter sudah terisi";
    }
    echo json_encode($result);
}

else {
    header("HTTP/1.1 400 Bad Request");
    $error = array(
        'error' => 'Method not Allowed'
    );

    echo json_encode($error);
}