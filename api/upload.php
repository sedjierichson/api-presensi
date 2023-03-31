<?php

error_reporting(E_ERROR | E_PARSE);
function uploadFile($file, $name, $directory) {
    $uploadStatus = 1;
    $allowTypes = array('jpg', 'jpeg', 'png');

    if (!empty($file[$name]['name'])) {
        $fileName = $file[$name]['name'];
        $fileSize = $file[$name]['size'];
        $tmpFileName = $file[$name]['tmp_name'];
        $fileError = $file[$name]['error'];

        $fileExtension = explode('.', $fileName);
        $fileExtension = strtolower(end($fileExtension));

        if (in_array($fileExtension, $allowTypes)) {
            if ($fileSize < 10000000) {
                $newFileName = uniqid() . "." . $fileExtension;

                if (move_uploaded_file($tmpFileName, $directory.$newFileName)) {
                    $uploadedFileDir = 'http://127.0.0.1:8888/api-presensi/api-presensi/api/'.$directory.$newFileName;
                    // $uploadedFileDir = 'http://192.168.0.102:8888/api-presensi/api-presensi/api/'.$directory.$newFileName;
                    // $uploadedFileDir = 'http://172.20.10.4:8888/api-presensi/api-presensi/api/'.$directory.$newFileName;
                } else {
                    $uploadStatus = 0;
                    $errorMsg = 'failed to move file';
                }
            } else {
                $uploadStatus = 0;
                $errorMsg = 'Ukuran File terlalu besar';
            }
        } else {
            $uploadStatus = 0;
            $errorMsg = 'file type not allowed';
        }
    } else {
        $uploadStatus = 0;
        $errorMsg = 'No Image uploaded';
    }

    if ($uploadStatus == 0) {
        $result = array(
            'status' => $uploadStatus,
            'message' => $errorMsg
        );
    } else {
        $result = array(
            'status' => $uploadStatus,
            'message' => $uploadedFileDir
        );
    }
    return $result;
    
}


?>