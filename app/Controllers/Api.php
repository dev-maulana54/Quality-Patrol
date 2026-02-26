<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Api extends Controller
{
    protected $db;
    protected $db2;
    public function __construct()
    {

        $this->db = \Config\Database::connect(); # koneksi database manual
        $this->db2 = env('database.henkaten.database'); # database kedua
    }
    public function getAtasan()
    {
        $id_section = $this->request->getGet('id_section');
        $id_departement = $this->request->getGet('id_departement');

        if (!$id_section || !$id_departement) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        $builder = $this->db->table("section s")
            ->select("
            s.id_section,
            s.section AS section_name,
            s.id_departement AS id_dept_local,
            d.departement AS departement_name,
            ks.nama AS kepala_seksi,
            kd.nama AS kepala_departement,
            COALESCE(ks.nama, kd.nama) AS nama_penanggung_jawab
        ")
            ->join("departement d", "d.id_departement = s.id_departement", "left")
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS ks",
                "ks.id_section = " . (int)$id_section . " AND ks.jabatan = 'Kepala Seksi'",
                "left",
                false
            )
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS kd",
                "kd.id_departement = " . (int)$id_departement . " AND kd.jabatan = 'Kepala Departemen'",
                "left",
                false
            )
            ->where("d.id_departement_henk", $id_departement);

        $result = $builder->get()->getRowArray();

        return $this->response->setJSON([
            'status' => true,
            'data' => $result
        ]);
    }
    public function getSectionByDepartement()
    {
        $id_departement = $this->request->getGet('id_departement');
        if (!$id_departement) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Parameter id_departement diperlukan'
            ]);
        }

        $builder = $this->db->table("section")
            ->select("id_section, section")
            ->where("id_departement", $id_departement);

        $sections = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => true,
            'data' => $sections
        ]);
    }
}
