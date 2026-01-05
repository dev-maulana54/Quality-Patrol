<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;

class Schedule extends BaseController
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
        $data['title'] = "Schedule | Quality Patrol";
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));

        $data['nama'] = $getdata_user['nama'];
        $data['getdata_auditor'] = $this->dataPatrol->getAlldata_karyawan();
        $data['role'] = session()->get('role');
        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $lop_area = $this->dataPatrol->get_dataSchedule_area();
        $simpan_data = [];
        $tanggal_plan_data = [];

        foreach ($lop_area as $la) {
            $area = $la['section'];
            $tanggal_patrol = $la['tanggal_patrol'];
            $tanggal_actual = isset($la['tanggal_actual']) ? $la['tanggal_actual'] : null;
            $id_schedule = $la['id_schedule'];
            // Simpan area jika belum ada
            if (!in_array($area, $simpan_data)) {
                $simpan_data[] = $area;
            }

            // PLAN - Konversi format tanggal
            if ($tanggal_patrol && $tanggal_patrol != 'NULL' && !empty($tanggal_patrol)) {
                // Ubah format dari d/m/Y ke Y-m-d
                $date_parts = explode('/', $tanggal_patrol);
                if (count($date_parts) == 3) {
                    $tanggal_formatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];

                    $tanggal_plan_data[] = [
                        'area' => $area,
                        'tanggal' => $tanggal_formatted,
                        'type' => 'plan',
                        'id_schedule' => $id_schedule
                    ];
                }
            }

            // ACTUAL - Konversi format tanggal
            if ($tanggal_actual && $tanggal_actual != 'NULL' && !empty($tanggal_actual)) {
                // Ubah format dari d/m/Y ke Y-m-d
                $date_parts = explode('/', $tanggal_actual);
                if (count($date_parts) == 3) {
                    $tanggal_formatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];

                    $tanggal_plan_data[] = [
                        'area' => $area,
                        'tanggal' => $tanggal_formatted,
                        'type' => 'actual',
                        'id_schedule' => $id_schedule
                    ];
                }
            }
        }

        $data['get_schedule_area'] = $simpan_data;
        $data['schedule_data'] = $tanggal_plan_data;

        return view('users/schedule', $data);
    }
}
