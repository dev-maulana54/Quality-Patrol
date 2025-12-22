<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DownloadController extends Controller
{
    public function file($namaFile)
    {
        $path = FCPATH . 'assets/uploads/' . $namaFile;

        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File tidak ditemukan');
        }

        return $this->response->download($path, null)->setFileName($namaFile);
    }
}
