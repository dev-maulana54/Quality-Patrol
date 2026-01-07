<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;

class Summary extends BaseController
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
    public function index()
    {

        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['title'] = "Summary Page | Quality Patrol";
        $data['nama'] = $getdata_user['nama'];


        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $data['role'] = session()->get('role');

        // DATA PER BULAN TAHUN SEKARANG
        $dataRaw = $this->dataPatrol->chartByYear();

        // Siapkan array default 12 bulan
        $open = array_fill(0, 12, 0);
        $inprogress = array_fill(0, 12, 0);
        $close = array_fill(0, 12, 0);
        $cancel = array_fill(0, 12, 0);
        $total = array_fill(0, 12, 0);

        foreach ($dataRaw as $row) {
            $bulan = (int)$row['bulan'] - 1; // index 0-11

            if ($row['status'] == '3') {
                $open[$bulan] = (int)$row['total'];
            } elseif ($row['status'] == '2') {
                $inprogress[$bulan] = (int)$row['total'];
            } elseif ($row['status'] == '1') {
                $close[$bulan] = (int)$row['total'];
            } elseif ($row['status'] == '4') {
                $cancel[$bulan] = (int)$row['total'];
            }

            $total[$bulan] += (int)$row['total'];
        }

        $data['open'] = $open;
        $data['progress'] = $inprogress;
        $data['close'] = $close;
        $data['cancel'] = $cancel;
        $data['total'] = $total;

        // data chart temuan quality patrol area
        $dataSection = $this->dataPatrol->totalTemuanByArea();

        $names = [];
        $values = [];
        $all = [];
        $total_open = [];
        $total_progress = [];
        $total_cancel = [];
        $total_close = [];

        foreach ($dataSection as $row2) {
            // Nama Section
            $names[] = $row2['nama_section'];

            $all[] = $row2;
            // Total Temuan Section
            $values[] = (int)$row2['total_temuan'];
            $total_open[] = $row2['total_open'];
            $total_progress[] = $row2['total_progress'];
            $total_close[] = $row2['total_close'];
            $total_cancel[] = $row2['total_cancel'];
            // $status[] = $row2['status'];
            // Kalau perlu nama departement:
            // $row['nama_departement']
        }
        //     echo "<pre>";
        //    var_dump($all);
        $data['areaNames']  = $names;
        $data['total_temuan'] = $values;
        $data['total_open'] = $total_open;
        $data['total_progress'] = $total_progress;
        $data['total_close'] = $total_close;
        $data['total_cancel'] = $total_cancel;


        // SEMUA DATA PER TAHUN NYA
        $datayear = $this->dataPatrol->chartByYearNow2();
        $t_open_year = [];
        $t_progress_year = [];
        $t_close_year = [];
        $t_cancel_year = [];
        foreach ($datayear as $dy) {

            if ($dy['status'] == '3') {
                $t_open_year[] = $dy['total'];
            } elseif ($dy['status'] == '2') {
                $t_progress_year[] = $dy['total'];
            } elseif ($dy['status'] == '1') {
                $t_close_year[] = $dy['total'];
            } elseif ($dy['status'] == '4') {
                $t_cancel_year[] = $dy['total'];
            }
        }

        $data['t_open_year']     = array_sum($t_open_year);
        $data['t_progress_year'] = array_sum($t_progress_year);
        $data['t_close_year']    = array_sum($t_close_year);
        $data['t_cancel_year']    = array_sum($t_cancel_year);

        return view('users/summary', $data);
    }
}
