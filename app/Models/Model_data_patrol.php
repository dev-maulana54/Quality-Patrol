<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_data_patrol extends Model
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect(); # koneksi database manual
    }

    public function getdata_userbyNPK($npk)
    {
        return $this->db->table('users')
            ->where('npk', $npk)
            ->get()
            ->getRowArray();
    }
    public function getdata_karyawan_byUsername($npk = null)
    {
        $npk = $npk ?? session()->get('npk');
        return $this->db->table('master_data_karyawan')
            ->where('npk', $npk)
            ->get()
            ->getRowArray();
    }

    public function get_data_patrolAll()
    {
        # ambil semua data dari tabel dt_temuan_patrol dan join id_dept dan id_seksi untuk menampilkan nama departemen dan nama seksi
        return $this->db->table('dt_temuan_patrol')
            ->select('
        dt_temuan_patrol.*,
        departement.departement AS departement_name,
        section.section AS section_name,
        pic_dept.departement AS pic_departement_name,
        pic_sec.section AS pic_section_name
    ')
            ->join('departement', 'dt_temuan_patrol.id_departement = departement.id_departement', 'left')
            ->join('section', 'dt_temuan_patrol.id_section = section.id_section', 'left')
            ->join('departement AS pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement', 'left')
            ->join('section AS pic_sec', 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section', 'left')
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_dept()
    {
        return $this->db->table('departement')
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_seksi()
    {
        return $this->db->table('section')
            ->get()
            ->getResultArray();
    }
    public function get_dataAllAuditor()
    {
        return $this->db->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function get_dataAllAuditee()
    {
        return $this->db->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function getAlldata_user()
    {
        return $this->db->table('users u')
            ->select('u.id as user_id, u.npk, u.role, m.nama, m.id_departement, m.id_section')
            ->join('master_data_karyawan m', 'u.npk = m.npk', 'left')
            ->get()
            ->getResultArray();
    }

    public function getDept_byId($id)
    {
        return $this->db->table('dt_dept')
            ->where('id_dept', $id)
            ->get()
            ->getRowArray();
    }
    public function getDeptSectionbyId($npk)
    {
        # ambil departemen dan seksi berdasarkan npk dari users tabel dan join ke master_data_karyawan nantinya dari situ ambil id_dept_user lalu cari seksi berdasarkan id_dept_user
        return $this->db->table('master_data_karyawan')
            ->join('users', 'master_data_karyawan.npk = users.npk', 'left')
            ->join('departement', 'master_data_karyawan.id_departement = departement.id_departement', 'left')
            ->join('section', 'master_data_karyawan.id_section = section.id_section', 'left')
            ->where('master_data_karyawan.npk', $npk)
            ->get()
            ->getRowArray();
    }
    public function getSeksi_byDeptId($deptId)
    {
        return $this->db->table('section')
            ->where('id_departement', $deptId)
            ->get()
            ->getResultArray();
    }
    public function getAlldata_karyawan()
    {
        return $this->db->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function getSection_andDeptByID($id_section)
    {
        return $this->db->table('section')->join('departement', 'section.id_departement = departement.id_departement', 'left')->where('id_section', $id_section)->get()->getRowArray();
    }



    public function test_query()
    {
        return $this->db->table('master_data_karyawan')

            ->get()
            ->getResultArray();
    }
    public function get_data_patrolByIdSection()
    {
        # ambil data dari tabel dt_temuan_patrol berdasarkan id_section dari user yang login dan join tabel nya dengan departement dan section
        # data akan tampil berdasarkan nama auditor, nama seksi nya yang sama
        $user       = $this->getdata_karyawan_byUsername(session()->get('npk'));
        $id_section = $user['id_section'];
        $id_departement = $user['id_departement'];

        return $this->db->table('dt_temuan_patrol')
            ->select('
        dt_temuan_patrol.*,

       
        departement.departement AS departement_name,

        
        section.section AS section_name,

      
        pic_dept.departement AS pic_departement_name,

  
        pic_sec.section AS pic_section_name,

      
        dt_temuan_patrol.nama_auditor AS auditor_name
    ')
            ->join('departement', 'dt_temuan_patrol.id_departement = departement.id_departement', 'left')
            ->join('section', 'dt_temuan_patrol.id_section = section.id_section', 'left')

            ->join('departement AS pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement', 'left')
            ->join('section AS pic_sec', 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section', 'left')
            ->groupStart()
            // ->where('dt_temuan_patrol.id_section', $id_section)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_section_id', $id_section)
            // ->orWhere('dt_temuan_patrol.id_departement', $id_departement)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_departement_id', $id_departement)
            ->where('dt_temuan_patrol.id_auditor', session()->get('npk'))
            ->groupEnd()
            ->get()
            ->getResultArray();
    }
    public function get_data_patrolByAuditee()
    {
        $user       = $this->getdata_karyawan_byUsername(session()->get('npk'));
        $id_section = $user['id_section'];
        $id_departement = $user['id_departement'];

        return $this->db->table('dt_temuan_patrol')
            ->select('
        dt_temuan_patrol.*,

       
        departement.departement AS departement_name,

        
        section.section AS section_name,

      
        pic_dept.departement AS pic_departement_name,

  
        pic_sec.section AS pic_section_name,

      
        dt_temuan_patrol.nama_auditor AS auditor_name
    ')
            ->join('departement', 'dt_temuan_patrol.id_departement = departement.id_departement', 'left')
            ->join('section', 'dt_temuan_patrol.id_section = section.id_section', 'left')
            // ->join('users', 'dt_temuan_patrol.id_auditor = users.id', 'left')
            ->join('departement AS pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement', 'left')
            ->join('section AS pic_sec', 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section', 'left')
            ->groupStart()
            ->where('dt_temuan_patrol.id_section', $id_section)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_section_id', $id_section)
            ->orWhere('dt_temuan_patrol.id_departement', $id_departement)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_departement_id', $id_departement)

            ->groupEnd()
            ->get()
            ->getResultArray();
    }
    public function chartByYearNow()
    {
        $year = date('Y');

        return $this->db->table('dt_temuan_patrol')
            ->select("MONTH(tanggal_patrol) AS bulan, status, COUNT(*) AS total")
            ->where('YEAR(tanggal_patrol)', $year)
            ->groupBy('MONTH(tanggal_patrol), status')
            ->orderBy('MONTH(tanggal_patrol)', 'ASC')
            ->get()
            ->getResultArray();
    }
    public function Filter_chartByYearNow($tahun)
    {
        return $this->db->table('dt_temuan_patrol')
            ->select("MONTH(tanggal_patrol) AS bulan, status, COUNT(*) AS total")
            ->where('YEAR(tanggal_patrol)', $tahun)
            ->groupBy('MONTH(tanggal_patrol), status')
            ->orderBy('MONTH(tanggal_patrol)', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function filterRangeDate($startDate, $endDate)
    {
        return $this->db->table('dt_temuan_patrol')
            ->select("
                dt_temuan_patrol.id_section,
                departement.departement AS nama_departemen,
                section.section AS nama_section,
                SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_count,
                SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_count,
                SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_count,
                COUNT(*) AS total
            ")
            ->join('departement', 'departement.id_departement = dt_temuan_patrol.id_departement', 'left')
            ->join('section', 'section.id_section = dt_temuan_patrol.id_section', 'left')
            ->where('tanggal_patrol >=', $startDate)
            ->where('tanggal_patrol <=', $endDate)
            ->groupBy([
                'dt_temuan_patrol.id_section',
                'departement.departement',
                'section.section'
            ])
            // ->orderBy('nama_area', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function filterDept($idDept)
    {
        $year = date('Y');
        return $this->db->table('dt_temuan_patrol')
            ->select("
    dt_temuan_patrol.id_departement,
    dt_temuan_patrol.status,
    MONTH(tanggal_patrol) AS bulan,
    SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_total,
    SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_total,
    SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_total,
     COUNT(*) AS total
    ")
            ->join('section', 'section.id_section = dt_temuan_patrol.id_section', 'left')
            ->where('dt_temuan_patrol.id_departement', $idDept)
            ->where('YEAR(tanggal_patrol)', $year)
            ->groupBy([

                'MONTH(tanggal_patrol)',
                ' dt_temuan_patrol.id_departement',
                'dt_temuan_patrol.status'

            ])
            ->get()
            ->getResultArray();
    }

    public function filterDept2($idDept)
    {
        $tahun = date('Y');
        return $this->db->table('dt_temuan_patrol')
            ->select("
     SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_total,
            SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_total,
            SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_total,
    ")
            ->join('section', 'section.id_section = dt_temuan_patrol.id_section', 'left')
            ->where('dt_temuan_patrol.id_departement', $idDept)
            ->where('YEAR(tanggal_patrol)', $tahun)
            ->get()->getRowArray();
    }

    public function chartByYearNow2()
    {
        $year = date('Y');

        return $this->db->table('dt_temuan_patrol')
            ->select("status, COUNT(*) AS total")
            ->where('YEAR(tanggal_patrol)', $year)
            ->groupBy('status')
            ->get()
            ->getResultArray();
    }
    public function totalTemuanByArea()
    {
        $tahun = date('Y');

        return $this->db->table('dt_temuan_patrol')
            ->select("
            dt_temuan_patrol.id_section,
            departement.departement AS nama_departemen,
            section.section AS nama_section,

            -- COUNT per status numeric
            SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS total_open,
            SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS total_progress,
            SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS total_close,

            COUNT(dt_temuan_patrol.id_temuan_patrol) AS total_temuan
        ")
            ->join('departement', 'departement.id_departement = dt_temuan_patrol.id_departement', 'left')
            ->join('section', 'section.id_section = dt_temuan_patrol.id_section', 'left')
            ->where('YEAR(dt_temuan_patrol.tanggal_patrol)', $tahun)
            ->groupBy([
                'dt_temuan_patrol.id_section',
                'departement.departement',
                'section.section'
            ])
            ->get()
            ->getResultArray();
    }
    public function get_dataSchedule_area($tahun = null, $bulan = null)
    {
        $user        = $this->getdata_karyawan_byUsername(session()->get('npk'));
        $id_section  = $user['id_section'];
        $role        = session()->get('role');

        if ($tahun == null) {
            $tahun = date('Y');
        }
        if ($bulan == null) {
            $bulan = date('m');
        }

        $builder = $this->db->table('dt_schedule ds')
            ->distinct()
            ->select('
        s.section,
        d.departement,
        ds.tanggal_patrol,
        ds.tanggal_actual,
        ds.id_schedule
    ')
            ->join('departement d', 'd.id_departement = ds.id_dept', 'left')
            ->join('section s', 's.id_section = ds.id_section', 'left')
            ->join('dt_temuan_patrol tp', 'tp.id_dt_schedule = ds.id_schedule', 'left')
            ->like('ds.tanggal_patrol', '/' . $bulan . '/' . $tahun, 'before');

        if (in_array($role, [2, 3])) {
            $builder->groupStart()
                ->where('tp.id_section', $id_section)
                ->orWhere('tp.pic_action_section_id', $id_section)
                ->orWhere('tp.id_auditor', session()->get('user_id'))
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function get_Alldata_schedule($id_section, $id_dept)
    {
        return $this->db->table('dt_schedule')
            ->select("
        section.section,
        departement.departement,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dt_schedule.tanggal_actual
    ")
            ->join('departement', 'departement.id_departement = dt_schedule.id_dept', 'left')
            ->join('section', 'section.id_section = dt_schedule.id_section', 'left')
            ->where('dt_schedule.id_dept !=', $id_dept)
            ->where('dt_schedule.id_section !=', $id_section)
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_scheduleByUser()
    {
        return $this->db->table('dt_schedule')
            ->select("
            section.section,
            departement.departement,
            dt_schedule.tanggal_patrol,
            dt_schedule.id_schedule,
            dt_schedule.tanggal_actual,
            STRING_AGG(master_data_karyawan.nama, ', ') AS nama_auditor
        ")
            ->join('departement', 'departement.id_departement = dt_schedule.id_dept', 'left')
            ->join('section', 'section.id_section = dt_schedule.id_section', 'left')
            ->join('dt_daftar_hadir', 'dt_daftar_hadir.id_schedule = dt_schedule.id_schedule', 'left')
            ->join('master_data_karyawan', 'master_data_karyawan.npk = dt_daftar_hadir.npk', 'left')
            ->where('dt_daftar_hadir.role', 2)
            ->where('dt_daftar_hadir.type_data', 'plan')
            ->groupBy("
            section.section,
            departement.departement,
            dt_schedule.tanggal_patrol,
            dt_schedule.id_schedule,
            dt_schedule.tanggal_actual
        ")
            ->get()
            ->getResultArray();
    }



    public function get_Alldata_scheduleByAuditee($id_section, $id_departement)
    {

        return $this->db->table('dt_schedule')
            ->select("
        section.section,
        departement.departement,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dt_schedule.tanggal_actual,
        master_data_karyawan.nama AS nama_auditor
    ")
            ->join('departement', 'departement.id_departement = dt_schedule.id_dept', 'left')
            ->join('section', 'section.id_section = dt_schedule.id_section', 'left')
            // ->join('users', 'users.id = dt_schedule.id_auditor', 'left')
            ->join('dt_daftar_hadir', 'dt_daftar_hadir.id_schedule = dt_schedule.id_schedule', 'left')
            ->join('master_data_karyawan', 'master_data_karyawan.npk = dt_daftar_hadir.npk', 'left')
            ->where('dt_schedule.id_section', $id_section)

            ->orWhere('dt_schedule.id_dept', $id_departement)
            ->get()
            ->getResultArray();
    }
    public function get_data_daftar_hadir($id)
    {
        # Kalau ada plan & actual untuk npk + id_schedule sama, dan keterangan keduanya = 1 → yang muncul plan saja.
        $builder = $this->db->table('dt_daftar_hadir dh');

        $builder->select("
        dh.id_sign,
        section.section,
        departement.departement,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dh.npk,
        dh.signed_at,
        dh.keterangan,
        dh.role,
        master_data_karyawan.nama,
        dh.type_data
    ");

        $builder->join('dt_schedule', 'dh.id_schedule = dt_schedule.id_schedule', 'left')
            ->join('departement', 'departement.id_departement = dt_schedule.id_dept', 'left')
            ->join('section', 'section.id_section = dt_schedule.id_section', 'left')
            ->join('master_data_karyawan', 'master_data_karyawan.npk = dh.npk', 'left')
            ->where('dh.id_schedule', $id);

        # Buang baris "actual" jika ada pasangan "plan" untuk npk+schedule yang sama,
        # dan keduanya keterangan=1
        $builder->where("
        NOT (
            dh.type_data = 'actual'
            AND dh.keterangan = 1
            AND EXISTS (
                SELECT 1
                FROM dt_daftar_hadir d2
                WHERE d2.id_schedule = dh.id_schedule
                  AND d2.npk = dh.npk
                  AND d2.keterangan = 1
                  AND d2.type_data = 'plan'
            )
        )
    ", null, false);

        return $builder->get()->getResultArray();
    }


    public function get_deptSection_bySchedule($schedule_id)
    {
        return $this->db->table('dt_schedule')
            ->select("
        dt_schedule.id_dept,
        departement.departement,
        dt_schedule.id_section,
        section.section,
        ks.nama AS kepala_seksi,
        kd.nama AS kepala_departement,
        COALESCE(ks.nama, kd.nama) AS nama_penanggung_jawab
    ")
            ->join(
                'departement',
                'departement.id_departement = dt_schedule.id_dept',
                'left'
            )
            ->join(
                'section',
                'section.id_section = dt_schedule.id_section',
                'left'
            )
            # Kepala Seksi
            ->join(
                'master_data_karyawan AS ks',
                "ks.id_section = dt_schedule.id_section
         AND ks.jabatan = 'Kepala Seksi'",
                'left'
            )
            # Kepala Departemen
            ->join(
                'master_data_karyawan AS kd',
                "kd.id_departement = dt_schedule.id_dept
         AND kd.jabatan = 'Kepala Departemen'",
                'left'
            )
            ->where('dt_schedule.id_schedule', $schedule_id)
            ->get()
            ->getRowArray();
    }
}
