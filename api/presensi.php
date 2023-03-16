<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Presensi.php";

$database = new Database();
$db = $database->connect();

$presensi = new Presensi($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $res = $presensi->getSingleData($_GET['id']);
    } 
    else if (isset($_GET['nik'])) {
        $res = $presensi->getDataByNIKPegawai($_GET['nik']);
    }
    else if (isset($_GET['id_kantor'])) {
        $res = $presensi->getDataByIdKantor($_GET['id_kantor']);
    } 
    else if (isset($_GET['nik_pegawai']) && isset($_GET['tanggal_absen'])) {
        $presensi->nik = (isset($_GET['nik_pegawai'])) ? $_GET['nik_pegawai'] : null;
        $presensi->tanggal = (isset($_GET['tanggal_absen'])) ? $_GET['tanggal_absen'] : null;
        // $res = $presensi->getUserSudahAbsenMasuk($_GET['nik'], $_GET['tanggal_absen']);
        $res = $presensi->getUserSudahAbsenMasuk();
    }
     else {
        $res = $presensi->getAllData();
    }

    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res
        );
    } else {
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

    if(isset($_POST['nik']) && isset($_POST['id_kantor']) && isset($_POST['tanggal']) && isset($_POST['jam_masuk']) && isset($_POST['foto']) ){
        $tmp = $presensi->insertPresensiMasukPegawai($_POST['nik'], $_POST['id_kantor'], $_POST['tanggal'], $_POST['jam_masuk'], $_POST['foto']);

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
}else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_presensi) && isset($data->jam_keluar)){
        // $kantor->nama = $data->nama;
        // $kantor->alamat = $data->alamat;
        $tmp = $presensi->updatePresensiKeluar($data->id_presensi, $data->jam_keluar);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Jam keluar berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Jam keluar gagal diupdate";
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