<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Pegawai.php";

$database = new Database();
$db = $database->connect();

$pegawai = new Pegawai($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['nik'])){
        $pegawai->nik = (isset($_GET['nik'])) ? $_GET['nik'] : null;
        $res = $pegawai->getCurrentUserData();

        if ($res) {
            $result = array(
                'status' => 1,
                'data' => $res
            );
        } else {
            $result = array(
                'status' => 0,
                'message' => 'NIK tidak ditemukan!'
            );
        }
    } 
    else {
        $res = $pegawai->getAllData();

        if ($res) {
            $result = array(
                'status' => 1,
                'data' => $res
            );
        } else {
            $result = array(
                'status' => 0,
                'message' => 'Data User tidak ditemukan'
            );
        }
    }

    echo json_encode($result);

} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $result = array(
        'status' => 0,
        'message' => ''
    );
    if (isset($_POST['nik']) && isset($_POST['security_code'])){
        $tmp = $pegawai->insertUserBaru($_POST['nik'], $_POST['security_code']);
        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;
        }
    } else {
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
    if (isset($data->nik) && isset($data->security_code)){
        // $kantor->nama = $data->nama;
        // $kantor->alamat = $data->alamat;
        $tmp = $pegawai->updatePinLogin($data->nik, $data->security_code);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Security code berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Security code gagal diupdate";
        }
    } else if (isset($data->nik) && isset($data->imei)){
        // $kantor->nama = $data->nama;
        // $kantor->alamat = $data->alamat;
        $tmp = $pegawai->updateIMEI($data->nik, $data->imei);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "IMEI berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "IMEI gagal diupdate";
        }
    }
     else {
        $result['status'] = 0;
        $result['message'] = "Pastikan parameter sudah terisi";
    }
    echo json_encode($result);
}
else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $result = array(
        'status' => 0,
        'message' => ''
    );
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id)) {
        $tmp = $pegawai->deactivateData($data->id);

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