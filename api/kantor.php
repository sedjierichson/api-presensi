<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Kantor.php";

$database = new Database();
$db = $database->connect();

$kantor = new Kantor($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = $kantor->getAllData();
    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res
        );
    } else {
        $result = array(
            'status' => 0,
            'message' => 'Data Izin tidak ditemukan'
        );
    }

    echo json_encode($result);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $result = array(
        'status' => 0,
        'message' => 'Test 1234'
    );
    if (isset($_POST['nama']) && isset($_POST['alamat'])){
        $tmp = $kantor->insertKantor($_POST['nama'], $_POST['alamat']);
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
    if (isset($data->id_kantor) && isset($data->nama) && isset($data->alamat)){
        // $kantor->nama = $data->nama;
        // $kantor->alamat = $data->alamat;
        $tmp = $kantor->updateDetailKantor($data->id_kantor, $data->nama, $data->alamat);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "kantor berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "kantor gagal diupdate";
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
        $tmp = $kantor->deactivateData($data->id);

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