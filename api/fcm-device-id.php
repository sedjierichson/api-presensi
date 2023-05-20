<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/FcmDeviceId.php";

$database = new Database();
$db = $database->connect();

$device = new FcmDeviceId($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));

    // if (isset($data->id_user) && isset($data->id_device)) {

    if (isset($_POST['nik']) && isset($_POST['token'])) {

        $resultInsertDeviceId = $device->insertData($_POST['nik'], $_POST['token']);

        if ($resultInsertDeviceId == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal ditambahkan";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = "Data berhasil ditambahkan";
        }

    } else {
        $result['status'] = 0;
        $result['message'] = "Pastikan semua parameter sudah lengkap";
    }

    echo json_encode($result);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $res = $device->getDataByIzin($_GET['id']);

    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res
        );
    } else {
        $result = array(
            'status' => 0,
            'message' => 'Data tidak ditemukan'
        );
    }
    echo json_encode($result);
}

else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_user) && isset($data->token)) {

        $resultInsertProgress = $device->removeData($data->id_user, $data->token);

        if ($resultInsertProgress == false) {
            $result['status'] = 0;
            $result['message'] = "Gagal";
        } else {
            $result['status'] = 1;
            $result['message'] = "Berhasil";
        }

    } else {
        $result['status'] = 0;
        $result['message'] = "Pastikan semua parameter sudah lengkap";
    }

    echo json_encode($result);
    
} else {
    header("HTTP/1.1 400 Bad Request");
    $error = array(
        'error' => 'Method not Allowed'
    );

    echo json_encode($error);
}
