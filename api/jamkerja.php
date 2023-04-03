<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/JamKerja.php";

$database = new Database();
$db = $database->connect();

$jamkerja = new JamKerja($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $res = $jamkerja->getSingleData($_GET['id']);
    } else {
        $res = $jamkerja->getAllData();
    }

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
else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id) && isset($data->jam_masuk) && isset($data->jam_pulang)){
        $tmp = $jamkerja->updateJamKerja($data->id, $data->jam_masuk, $data->jam_pulang);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Jam kerja berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Jam kerja gagal diupdate";
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