<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Log.php";
require "upload.php";

$database = new Database();
$db = $database->connect();

$log = new Log($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = $log->getAllData();

    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res,
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

    if(isset($_POST['jam']) && isset($_POST['jarak']) && isset($_POST['keterangan'])){
        $tmp = $log->insertLog($_POST['jarak'], $_POST['jam'], $_POST['keterangan']);
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

} else {
    header("HTTP/1.1 400 Bad Request");
    $error = array(
        'error' => 'Method not Allowed'
    );

    echo json_encode($error);
}