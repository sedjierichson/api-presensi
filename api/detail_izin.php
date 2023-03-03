<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/DetailIzin.php";

$database = new Database();
$db = $database->connect();

$detailizin = new DetailIzin($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //get semua data materi aktif (status = 1)
    if (isset($_GET['id'])) {
        $res = $detailizin->getSingleData($_GET['id']);
    }
    else if(isset($_GET['nik_pegawai'])){
        $res = $detailizin->getDataByNIKPegawai($_GET['nik_pegawai']);
    }
    else{
        $res = $detailizin->getAllData();
    }

    if ($res) {
        $result = array(
            'status' => 1,
            'data' => $res
        );
    } else {
        $result = array(
            'status' => 0,
            'message' => 'Data Webinar tidak ditemukan'
        );
    }

    echo json_encode($result);
    
    // else{
    // $res = $detailizin->getAllData();
    // $num = $res->rowCount();

    // if ($num > 0) {
    //     $result = array(
    //         'status' => 1,
    //         'data' => array()
    //     );

    //     while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    //         extract($row);
    //         $user_item = array(
    //             'id' => $id,
    //             'id_jenis_izin' => $id_jenis_izin,
    //             'tipe_izin' => $tipe_izin,
    //             'nik_pegawai' => $nik_pegawai,
    //             'nik_atasan' => $nik_atasan,
    //             'tanggal_awal' => $tanggal_awal,
    //             'tanggal_akhir' => $tanggal_akhir,
    //             'jam_awal' => $jam_awal,
    //             'jam_akhir' => $jam_akhir,
    //             'alasan' => $alasan,
    //             'tempat_tujuan' => $tempat_tujuan,
    //             'uraian_tugas' => $uraian_tugas,
    //         );
    //         array_push($result['data'], $user_item);
    //     }
    // // } 
    // else {
    //     $result = array(
    //         'status' => 0,
    //         'message' => 'No users found'
    //     );
    // }
    // echo json_encode($result);
// }
    
}