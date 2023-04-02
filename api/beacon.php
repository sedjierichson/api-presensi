<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Beacon.php";

$database = new Database();
$db = $database->connect();

$beacon = new Beacon($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $res = $beacon->getSingleData($_GET['id']);
    } else {
        $res = $beacon->getAllData();
    }

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

    if(isset($_POST['id_kantor']) && isset($_POST['uuid']) && isset($_POST['nama']) && isset($_POST['lokasi'])){
        $tmp = $beacon->insertBeacon($_POST['id_kantor'],$_POST['uuid'],$_POST['nama'],$_POST['lokasi']);

        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Beacon gagal diinput";
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
}
else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $result = array(
        'status' => 0,
        'message' => ''
    );

    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id) && isset($data->nama) && isset($data->lokasi)){
        $tmp = $beacon->updateBeacon($data->id, $data->nama, $data->lokasi);

        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Beacon berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Beacon gagal diupdate";
        }
    } else {
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
        $tmp = $beacon->deactivateData($data->id);

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