<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;

class Temuan_patrol extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {
        // Cek apakah session isLoggedIn tidak ada atau tidak bernilai true, maka redirect ke login
        if (!session()->get('isLoggedIn') || session()->get('isLoggedIn') !== true) {
            header('Location: ' . base_url('login'));
            exit(); // ✅ WAJIB pakai exit() agar script berhenti
        }

        // Cek juga apakah npk tidak ada di session
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
        $data['data_schedule'] = $this->dataPatrol->get_Alldata_scheduleByUser();
        $data['nama'] = $getdata_user['nama'];
        $data['npk'] = session()->get('npk');
        $data['id_section_user'] = $getdata_user['id_section'];
        $data['id_dept_user'] = $getdata_user['id_departement'];
        $data['role'] = session()->get('role');
        if (session()->get('role') == 'Administrator') {
            $data['data_patrol'] = $this->dataPatrol->get_data_patrolAll();
        } else {
            $data['data_patrol'] = $this->dataPatrol->get_data_patrolByIdSection();
        }

        $data['schedule_audit'] = $this->dataPatrol->get_Alldata_schedule($getdata_user['id_section'], $getdata_user['id_departement']);

        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $agent = $this->request->getUserAgent();
        if ($agent->isMobile()) {

            return view('mobile/data_patrol', $data);
        } else {

            // return view('mobile/data_patrol', $data);
            return view('users/temuan_patrol', $data);
            // return view('desktop/users/temuan_patrol', $data);
        }
    }
    public function start_audit()
    {
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['title'] = "Summary Page | Quality Patrol";
        $data['nama'] = $getdata_user['nama'];
        $data['all_dept'] = $this->dataPatrol->get_Alldata_dept();
        $agent = $this->request->getUserAgent();
        if ($agent->isMobile()) {

            return view('mobile/start_patrol', $data);
        } else {

            // return view('info/info_pengembangan', $data);
            return view('users/start_patrol', $data);
            // return view('users/temuan_patrol', $data);
            // return view('desktop/users/temuan_patrol', $data);
        }
    }
    #function untuk melakukan sign schedule
    public function sign($id)
    {
        $data['title'] = "Temuan Patrol | Quality Patrol";
        $data['data_daftar_hadir'] = $this->dataPatrol->get_data_daftar_hadir($id);
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['data_karyawan'] = $this->dataPatrol->getAlldata_karyawan();
        $data['id'] = $id;
        $data['npk'] = session()->get('npk');
        $data['nama'] = $getdata_user['nama'];
        $data['role'] = session()->get('role');
        return view('users/sign', $data);
    }
    #function untuk mengakses temuan Patrol sebagai auditee
    public function auditee()
    {
        $data['title'] = "Temuan Patrol | Quality Patrol";
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $getuserLog = $this->dataPatrol->getdata_userbyNPK(session()->get('npk'));
        $data['data_schedule'] = $this->dataPatrol->get_Alldata_scheduleByAuditee($getdata_user['id_section'], $getdata_user['id_departement']);
        $data['nama'] = $getdata_user['nama'];
        $data['npk'] = session()->get('npk');
        $data['id_section_user'] = $getdata_user['id_section'];
        $data['id_dept_user'] = $getdata_user['id_departement'];
        $data['role'] = session()->get('role');

        $data['data_patrol_auditee'] = $this->dataPatrol->get_data_patrolByAuditee();


        $data['schedule_audit'] = $this->dataPatrol->get_Alldata_schedule($getdata_user['id_section'], $getdata_user['id_departement']);

        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $agent = $this->request->getUserAgent();
        if ($agent->isMobile()) {
            return view('mobile/data_patrol_auditee', $data);
        } else {
            // return view('mobile/data_patrol_auditee', $data);
            return view('users/temuan_auditee', $data);
        }
    }
    public function daftar_hadir()
    {
        $data['title'] = "Daftar Hadir | Quality Patrol";
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['nama'] = $getdata_user['nama'];
        $data['role'] = session()->get('role');

        $data['data_schedule'] = $this->dataPatrol->get_Alldata_scheduleByUser();
        return view('info/info_pengembangan', $data);
        // return view('users/daftar_hadir', $data);
    }
    public function preview($file)
    {
        $data['namafile'] = $file;
        $data['title'] = 'Preview PDF';
        return view('pdf', $data);
    }
}
