<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/Kantor.php";

$database = new Database();
$db = $database->connect();

$detailizin = new DetailIzin($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = $detailizin->getAllData();
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
}