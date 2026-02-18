<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;

class Admin extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {
        $this->dataPatrol = new Model_data_patrol();
    }
    public function mdata_user()
    {
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['title'] = "Master Data User | Admin";
        $data['nama'] = $getdata_user['nama'];

        $data['role'] = session()->get('role');
        $data['data_user'] = $this->dataPatrol->getAlldata_user();
        $data['data_karyawan'] = $this->dataPatrol->getAlldata_karyawan();
        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $data['data_seksi'] = $this->dataPatrol->get_Alldata_seksi();

        return view('admin/mdata_user', $data);
    }
    public function mdata_department(): string
    {
        $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));
        $data['title'] = "Master Data User | Admin";
        $data['nama'] = $getdata_user['nama'];
        $data['role'] = session()->get('role');
        $data['data_dept'] = $this->dataPatrol->get_Alldata_dept();
        $data['data_dept_henk'] = $this->dataPatrol->get_Alldata_dept_henk();
        $data['data_karyawan'] = $this->dataPatrol->getAlldata_karyawan();
        return view('admin/mdata_departement', $data);
    }
    public function test_upload()
    {
        #todo : buatlah script untuk mengecek folder public/assets/uploads itu ada atau tidak gunakan selain writepath
        $upload_path = FCPATH . '/assets/uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
            return "Folder created at: " . $upload_path;
        } else {
            return "Folder already exists at: " . $upload_path;
        }
    }
}
