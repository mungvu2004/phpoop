<?php

namespace App;

class Controller
{
    public function logError($message)
    {
        $date = date('d-m-Y');

        error_log($message, 3, "storage/logs/$date.log");
    }
    public function uploadFile(array $file, $folder = null)
    {
        $fileTmpPath = $file['tmp_name'];
        $fileName = time() . '-' . $file['name'];

        $uploaDir = 'storage/uploads/' . $folder . '/';

        if(!is_dir($uploaDir)) {
            mkdir($uploaDir, 0777, true);
        }

        $destPath = $uploaDir . $fileName;

        return move_uploaded_file($fileTmpPath, $destPath);
    }
}