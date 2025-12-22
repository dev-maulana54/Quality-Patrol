<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;

class Temuan_patrol extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {
        // Cek apakah session isLoggedIn ada dan bernilai true
        if (!session()->get('isLoggedIn') || session()->get('isLoggedIn') !== true) {
            header('Location: ' . base_url('login'));
            exit(); // ✅ WAJIB pakai exit() agar script berhenti
        }

        // Cek juga apakah npk ada di session
        if (!session()->get('npk')) {
            header('Location: ' . base_url('login'));
            exit(); // ✅ WAJIB pakai exit()
        }
        $this->dataPatrol = new Model_data_patrol();
    }
    public function index(): string
    {
        $data['title'] = "Temuan Patrol | Quality Patrol";
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $getuserLog = $this->dataPatrol->getdata_userbyNPK(session()->get('npk'));
        $data['data_schedule'] = $this->dataPatrol->get_Alldata_scheduleByUser($getuserLog['id']);
        $data['nama'] = $getdata_user['nama'];

        $data['role'] = session()->get('role');
        if (session()->get('role') == 1) {
            $data['data_patrol'] = $this->dataPatrol->get_data_patrolAll();
        } elseif (session()->get('role') == 2) {
            $data['data_patrol'] = $this->dataPatrol->get_data_patrolByIdSection();
        } else {
            $data['data_patrol'] = $this->dataPatrol->get_data_patrolByIdSection();
        }
        if (session()->get('role') == 1) {
            $data['schedule_audit'] = $this->dataPatrol->get_Alldata_schedule();
        } elseif (session()->get('role') == 2) {
            $data['schedule_audit'] = $this->dataPatrol->get_Alldata_schedulebyID($getuserLog['id']);
        }
        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        return view('users/temuan_patrol', $data);
    }

    public function sign($id)
    {
        $data['title'] = "Temuan Patrol | Quality Patrol";
        $data['data_daftar_hadir'] = $this->dataPatrol->get_data_daftar_hadir($id);
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['data_karyawan'] = $this->dataPatrol->getAlldata_karyawan();
        $data['id'] = $id;
        $data['nama'] = $getdata_user['nama'];
        $data['role'] = session()->get('role');
        return view('users/sign', $data);
    }
    public function preview($file)
    {
        $data['namafile'] = $file;
        $data['title'] = 'Preview PDF';
        return view('pdf', $data);
    }
}
