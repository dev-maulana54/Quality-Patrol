<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use App\Models\Model_data_patrol;

class testDB extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {
        $this->dataPatrol = new Model_data_patrol();
    }
    public function index()
    {
        // $patrol = $this->dataPatrol->getAll();
        // echo "<pre>";
        // var_dump($patrol);




        $db = Database::connect();
        if ($db->connect()) {
            echo "Koneksi berhasil!";
        } else {
            echo "Koneksi gagal!";
        }
        $cek = $this->dataPatrol->test_query();
        echo "<pre>";
        var_dump($cek);
        // $data = [
        //     'tanggal_patrol'   => '12 November 20252',
        //     'nama_auditor'  => 'Maulana Saepul Akbar2',
        //     'nama_auditee' => 'Uswatun Khasanah2',
        //     'tp_area' => 'Formation22',
        //     'deskripsi_temuan' => 'Ini adalah deskripsi temuan patrol kualitas2',
        //     'analisa_penyebab' => 'ini adalah analisa penyebab temuan patrol kualitas2.',
        //     'action' => 'ini adalah action yang diambil untuk temuan patrol kualitas.2',
        //     'pic_action' => 'Formation',
        //     'due_date' => '17 November 2025',
        //     'status' => 1,
        // ];

        // // 3) insert via query builder
        // $ok = $db->table('dt_temuan_patrol')->insert($data);

        // if ($ok) {
        //     $idBaru = $db->insertID();   // ambil ID auto-increment (kalau ada)
        //     return "Insert OK. ID: {$idBaru}";
        // }



        // cek error (opsional)
        // $error = $db->error(); // ['code'=>..., 'message'=>...]
        // return "Insert gagal: " . ($error['message'] ?? 'unknown');
    }
}
