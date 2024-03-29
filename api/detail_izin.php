<?php
error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require "../Database.php";
require "../models/DetailIzin.php";
require "send-notifications.php";
require "../models/FcmDeviceId.php";
require "../models/Presensi.php";

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
    else if(isset($_GET['nik_atasan'])){
        $res = $detailizin->getDataByNIKAtasan($_GET['nik_atasan']);
    }
    else if(isset($_GET['id_izin'])){
        $res = $detailizin->getDataByJenisIzin($_GET['id_izin']);
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
            'message' => 'Data Izin tidak ditemukan'
        );
    }

    echo json_encode($result);

} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $result = array(
        'status' => 0,
        'message' => 'Test 1234'
    );

    // $data = json_decode(file_get_contents("php://input"));
    // if (isset($data->nik_pegawai) && isset($data->nik_atasan) && isset($data->tanggal_izin) && isset($data->jam_izin_pulang) && isset($data->alasan)){
    if(isset($_POST['nik_pegawai']) && isset($_POST['nik_atasan']) && isset($_POST['tanggal_izin']) && isset($_POST['jam_izin_pulang']) && isset($_POST['alasan']) && isset($_POST['tanggal_pengajuan'])){
        $tmp = $detailizin->insertIzinPulangCepat($_POST['nik_pegawai'], $_POST['nik_atasan'], $_POST['tanggal_izin'], $_POST['jam_izin_pulang'], $_POST['alasan'], $_POST['tanggal_pengajuan']);

        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;

            $fcm = new FcmDeviceId($db);
            $title = "Ada Pengajuan Izin Baru";
            $body = "Izin tipe Pulang Kantor Lebih Awal";
            $icon = "";
            $url = "";
            $deviceId = $fcm->getDataByIzin($result['message']);

            if ($deviceId->rowCount() > 0) {
                while ($row = $deviceId->fetch(PDO::FETCH_ASSOC)) {
                    sendPush($row['keyClient'], $title, $body, $icon, $url);
                }
            }
            // sendPush($keyclient, $title, $body, $icon, $url);
        }
   
    } else if (isset($_POST['nik_pegawai']) && isset($_POST['nik_atasan']) && isset($_POST['tanggal_izin']) && isset($_POST['jam_awal']) && isset($_POST['jam_akhir']) &&isset($_POST['alasan']) && isset($_POST['tanggal_pengajuan'])){
        $tmp = $detailizin->insertIzinMeninggalkanKantor($_POST['nik_pegawai'], $_POST['nik_atasan'], $_POST['tanggal_izin'], $_POST['jam_awal'], $_POST['jam_akhir'], $_POST['alasan'], $_POST['tanggal_pengajuan']);
        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;

            $fcm = new FcmDeviceId($db);
            $title = "Ada Pengajuan Izin Baru";
            $body = "Izin tipe Meninggalkan Kantor Sementara";
            $icon = "";
            $url = "";
            $deviceId = $fcm->getDataByIzin($result['message']);

            if ($deviceId->rowCount() > 0) {
                while ($row = $deviceId->fetch(PDO::FETCH_ASSOC)) {
                    sendPush($row['keyClient'], $title, $body, $icon, $url);
                }
            }
        }
    } else if (isset($_POST['nik_pegawai']) && isset($_POST['nik_atasan']) && isset($_POST['tanggal_awal']) && isset($_POST['tanggal_akhir']) && isset($_POST['uraian_tugas']) && isset($_POST['tempat_tujuan']) && isset($_POST['tanggal_pengajuan'])){
        $tmp = $detailizin->insertIzinSuratTugas($_POST['nik_pegawai'], $_POST['nik_atasan'], $_POST['tanggal_awal'], $_POST['tanggal_akhir'], $_POST['uraian_tugas'], $_POST['tempat_tujuan'], $_POST['tanggal_pengajuan']);
        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;

            $fcm = new FcmDeviceId($db);
            $title = "Ada Pengajuan Izin Baru";
            $body = "Izin tipe Surat Tugas";
            $icon = "";
            $url = "";
            $deviceId = $fcm->getDataByIzin($result['message']);

            if ($deviceId->rowCount() > 0) {
                while ($row = $deviceId->fetch(PDO::FETCH_ASSOC)) {
                    sendPush($row['keyClient'], $title, $body, $icon, $url);
                }
            }
        }
    } else if (isset($_POST['nik_pegawai']) && isset($_POST['nik_atasan']) && isset($_POST['tanggal_lupa_absen']) && isset($_POST['jam_awal']) && isset($_POST['jam_akhir']) &&isset($_POST['alasan']) && isset($_POST['tanggal_pengajuan'])){
        $tmp = $detailizin->insertIzinLupaAbsen($_POST['nik_pegawai'], $_POST['nik_atasan'], $_POST['tanggal_lupa_absen'], $_POST['jam_awal'], $_POST['jam_akhir'], $_POST['alasan'], $_POST['tanggal_pengajuan']);
        if ($tmp == false) {
            $result['status'] = 0;
            $result['message'] = "Data gagal diinput";
        } else {
            header("HTTP/1.1 201 Created");
            $result['status'] = 1;
            $result['message'] = $tmp;

            $fcm = new FcmDeviceId($db);
            $title = "Ada Pengajuan Izin Baru";
            $body = "Izin tipe Lupa Absen";
            $icon = "";
            $url = "";
            $deviceId = $fcm->getDataByIzin($result['message']);

            if ($deviceId->rowCount() > 0) {
                while ($row = $deviceId->fetch(PDO::FETCH_ASSOC)) {
                    sendPush($row['keyClient'], $title, $body, $icon, $url);
                }
            }
        }
    }
    else {
        $result['status'] = 0;
        $result['message'] = "Pastikan semua parameter sudah lengkap";
    }
    echo json_encode($result);

} else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $result = array(
        'status' => 0,
        'message' => '',
        'message2' => ''
    );

    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->id) && isset($data->tanggal_respon) && isset($data->mode)){
        if($data->mode == "terima"){
            $tmp = $detailizin->terimaIzin($data->id,$data->tanggal_respon);
            if ($data->id_jenis_izin == "4"){
                $presensi = new Presensi($db);
                $tmp2 = $presensi->insertPresensiMasukPegawaiByAdmin($data->nik, $data->id_kantor, $data->tanggal, $data->jam_masuk, $data->jam_keluar, 'http://127.0.0.1:8888/api-presensi/api-presensi/api/files/'.$data->img_name,  $data->kategori, $data->is_history);
                if ($tmp2){
                    $result['message2'] = 'Berhasil';
                } else {
                    $result['message2'] = 'Gagal';
                }
            }
        } else if ($data->mode == "tolak"){
            $tmp = $detailizin->tolakIzin($data->id, $data->tanggal_respon);
        }
        
        if ($tmp) {
            $result['status'] = 1;
            $result['message'] = "Status izin berhasil diupdate";
        } else {
            $result['status'] = 0;
            $result['message'] = "Status izin gagal diupdate";
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
        $tmp = $detailizin->deactivateData($data->id);

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