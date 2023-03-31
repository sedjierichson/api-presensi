<?php
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
    else if (isset($_GET['nik'])) {
        $res = $presensi->getDataByNIKPegawai($_GET['nik']);
    }
    else if (isset($_GET['id_kantor'])) {
        $res = $presensi->getDataByIdKantor($_GET['id_kantor']);
    } 
    else if (isset($_GET['nik_pegawai']) && isset($_GET['tanggal_absen'])) {
        $presensi->nik = (isset($_GET['nik_pegawai'])) ? $_GET['nik_pegawai'] : null;
        $presensi->tanggal = (isset($_GET['tanggal_absen'])) ? $_GET['tanggal_absen'] : null;
        // if($_GET['mode']){
        //     $res = $presensi -> getUserSudahAbsenLengkap();
        // } else {
        $res = $presensi->getUserSudahAbsenMasuk();
        // }

    }
    else if (isset($_GET['nikk']) && isset($_GET['tahun']) && isset($_GET['bulan'])) {
        $presensi->nikk = (isset($_GET['nikk'])) ? $_GET['nikk'] : null;
        $presensi->tahun = (isset($_GET['tahun'])) ? $_GET['tahun'] : null;
        $presensi->bulan = (isset($_GET['bulan'])) ? $_GET['bulan'] : null;
        // $res = 'Masuk sini';
        $res = $presensi -> getDataByNIKPegawaidanFilterTahunBulan();
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

    if(isset($_POST['nik']) && isset($_POST['id_kantor']) && isset($_POST['tanggal']) && isset($_POST['jam_masuk']) && isset($_POST['image']) && isset($_POST['img_name'])){
        // $uploadResult = uploadFile($_FILES, 'foto', 'files/');

        $image = $_POST['image'];
        $name = $_POST['img_name'];
        $realImage = base64_decode($image);

        file_put_contents('files/'.$name, $realImage);
        
        // if ($uploadResult['status'] != 1) {
        //     $result['status'] = 0;
        //     $result['message'] = $uploadResult['message'];
            
        // } else {
            $tmp = $presensi->insertPresensiMasukPegawai($_POST['nik'], $_POST['id_kantor'], $_POST['tanggal'], $_POST['jam_masuk'], 'http://127.0.0.1:8888/api-presensi/api-presensi/api/files/'.$_POST['img_name']);
            if ($tmp == false) {
                $result['status'] = 0;
                $result['message'] = "Data gagal diinput";
            } else {
                header("HTTP/1.1 201 Created");
                $result['status'] = 1;
                $result['message'] = "Berhasil absen masuk";
            }
        // }
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