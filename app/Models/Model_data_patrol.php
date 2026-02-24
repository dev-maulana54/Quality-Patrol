<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_data_patrol extends Model
{
    protected $db;
    protected $henkaten;
    protected $db1;
    protected $db2;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect(); # koneksi database manual
        $this->henkaten = \Config\Database::connect('second'); # koneksi database kedua
        $this->db1 = env('database.default.database'); # database default
        $this->db2 = env('database.henkaten.database'); # database kedua
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
        return $this->henkaten->table('master_data_karyawan')
            ->where('npk', $npk)
            ->get()
            ->getRowArray();
    }

    public function get_data_patrolAll()
    {
        # ambil semua data dari tabel dt_temuan_patrol dan join id_dept dan id_seksi untuk menampilkan nama departemen dan nama seksi
        return $this->db->table('dt_temuan_patrol')
            ->select("
        dt_temuan_patrol.*,
        d.departement AS departement_name,
        s.section AS section_name,
        pic_dept.departement AS pic_departement_name,
        pic_sec.section AS pic_section_name
    ")
            ->join(
                'departement d',
                'dt_temuan_patrol.id_departement = d.id_departement_henk',
                'left'
            )
            ->join(
                'section s',
                '(dt_temuan_patrol.id_section = s.id_section_henk 
          OR 
         (dt_temuan_patrol.id_section = s.id_section AND s.id_section_henk = 0))',
                'left',
                false
            )
            ->join(
                'departement pic_dept',
                'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement_henk',
                'left'
            )
            ->join(
                'section pic_sec',
                '(dt_temuan_patrol.pic_action_section_id = pic_sec.id_section_henk 
          OR 
         (dt_temuan_patrol.pic_action_section_id = pic_sec.id_section AND pic_sec.id_section_henk = 0))',
                'left',
                false
            )
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_dept()
    {
        return $this->db->table('departement')
            ->where('keterangan_update', 'new')
            ->orderBy('departement', 'ASC') // Urut A-Z
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_dept_henk()
    {
        return $this->henkaten->table('departement')
            ->where('keterangan_update', 'new')
            ->orderBy('departement', 'ASC') // Urut A-Z
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
        return $this->henkaten->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function get_dataAllAuditee()
    {
        return $this->henkaten->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function getAlldata_user()
    {
        return $this->db
            ->table("{$this->db1}.dbo.users u")
            ->select('u.id as user_id, u.npk, u.role, m.nama, m.id_departement, m.id_section')
            ->join("{$this->db2}.dbo.master_data_karyawan m", 'u.npk = m.npk', 'left')
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
        return $this->db
            ->table("{$this->db2}.dbo.master_data_karyawan m")
            ->select('m.*, u.*, d.*, s.*')
            ->join("{$this->db1}.dbo.users u", 'm.npk = u.npk', 'left')
            ->join("departement d", 'm.id_departement = d.id_departement', 'left')
            ->join("section s", 'm.id_section = s.id_section', 'left')
            ->where('m.npk', $npk)
            ->get()
            ->getRowArray();
    }
    public function getSeksi_byDeptId($deptId)
    {
        return $this->db->table('section')
            ->where('id_departement', $deptId)
            ->where('keterangan_update', 'new')
            ->orderBy('section', 'ASC') // Urut A-Z
            ->get()
            ->getResultArray();
    }
    public function getAlldata_karyawan()
    {
        return $this->henkaten->table('master_data_karyawan')
            ->get()
            ->getResultArray();
    }
    public function getSection_andDeptByID($id_section)
    {
        return $this->db->table('section')
            ->join("departement", 'section.id_departement = departement.id_departement', 'left')
            ->where('id_section', $id_section)
            ->get()
            ->getRowArray();
    }
    public function getSection_andDeptByIDNEW($id_departement)
    {
        return $this->db->table('departement')
            ->where('id_departement_henk', $id_departement)
            ->get()
            ->getRowArray();
    }
    public function getDataDept_basedOnID($id_dept)
    {
        return $this->db->table('departement')
            ->where('id_departement_henk', $id_dept)
            ->get()
            ->getRowArray();
    }
    public function tb_section($id_section)
    {
        return $this->db->table('section')
            ->join("departement", 'section.id_departement = departement.id_departement', 'left')
            ->where('id_section', $id_section)
            ->get()
            ->getRowArray();
    }


    public function test_query()
    {
        return $this->henkaten->table('master_data_karyawan')

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
            ->select("
        dt_temuan_patrol.*,
        d.departement AS departement_name,
        s.section AS section_name,
        pic_dept.departement AS pic_departement_name,
        pic_sec.section AS pic_section_name,
        dt_temuan_patrol.nama_auditor AS auditor_name
    ")
            ->join('departement d', 'dt_temuan_patrol.id_departement = d.id_departement_henk', 'left')
            ->join(
                'section s',
                '(dt_temuan_patrol.id_section = s.id_section_henk OR (dt_temuan_patrol.id_section = s.id_section AND s.id_section_henk = 0))',
                'left',
                false
            )
            ->join('departement pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement_henk', 'left')
            ->join(
                'section pic_sec',
                '(dt_temuan_patrol.pic_action_section_id = pic_sec.id_section_henk OR (dt_temuan_patrol.pic_action_section_id = pic_sec.id_section AND pic_sec.id_section_henk = 0))',
                'left',
                false
            )
            ->where('dt_temuan_patrol.id_auditor', session()->get('npk'))
            ->orderBy("CONVERT(date, tanggal_patrol, 106) DESC", false)
            ->get()
            ->getResultArray();



        // ->where('dt_temuan_patrol.id_section', $id_section) // Hilangkan Command jika ingin munculkan data untuk pic section // 
        // ->orWhere('dt_temuan_patrol.pic_action_section_id', $id_section) // 
        // ->orWhere('dt_temuan_patrol.id_departement', $id_departement) // Hilangkan Command jika ingin munculkan data untuk pic section // 
        // ->orWhere('dt_temuan_patrol.pic_action_departement_id', $id_departement)
    }
    public function get_data_patrolByAuditee()
    {
        $user       = $this->getdata_karyawan_byUsername(session()->get('npk'));
        $id_section = $user['id_section'];
        $id_departement = $user['id_departement'];

        return $this->db->table('dt_temuan_patrol')
            ->select('
        dt_temuan_patrol.*,

       
        d.departement AS departement_name,

        
        s.section AS section_name,

      
        pic_dept.departement AS pic_departement_name,

  
        pic_sec.section AS pic_section_name,

      
        dt_temuan_patrol.nama_auditor AS auditor_name
    ')
            ->join("departement d", 'dt_temuan_patrol.id_departement = d.id_departement_henk', 'left')
            ->join("section s", 'dt_temuan_patrol.id_section = s.id_section_henk', 'left')
            // ->join('users', 'dt_temuan_patrol.id_auditor = users.id', 'left')
            ->join("departement AS pic_dept", 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement_henk', 'left')
            ->join("section AS pic_sec", 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section_henk', 'left')
            ->groupStart()
            ->where('dt_temuan_patrol.id_section', $id_section)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_section_id', $id_section)
            ->orWhere('dt_temuan_patrol.id_departement', $id_departement)
            ->orderBy("CONVERT(date, tanggal_patrol, 106) DESC", false)
            // Hilangkan Command jika ingin munculkan data untuk pic section
            // ->orWhere('dt_temuan_patrol.pic_action_departement_id', $id_departement)

            ->groupEnd()
            ->get()
            ->getResultArray();
    }
    public function chartByYear()
    {


        return $this->db->table('dt_temuan_patrol')
            ->select("MONTH(tanggal_patrol) AS bulan, status, COUNT(*) AS total")

            ->groupBy('MONTH(tanggal_patrol), status')
            ->orderBy('MONTH(tanggal_patrol)', 'ASC')
            ->get()
            ->getResultArray();
    }
    public function totalAudience()
    {
        return $this->db->table('users')
            ->select("
        SUM(CASE WHEN role = 2 THEN 1 ELSE 0 END) AS total_auditor,
        SUM(CASE WHEN role = 3 THEN 1 ELSE 0 END) AS total_auditee
    ")
            ->get()
            ->getRowArray();
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
        $tsStart = strtotime($startDate);
        $tsEnd   = strtotime($endDate);

        if ($tsStart === false || $tsEnd === false) return [];

        $start = date('Y-m-d', $tsStart);
        $end   = date('Y-m-d', $tsEnd);

        return $this->db->table('dt_temuan_patrol')
            ->select("
            dt_temuan_patrol.id_section,
            departement.departement AS nama_departemen,
            section.section AS nama_section,
            SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_count,
            SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_count,
            SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_count,
            SUM(CASE WHEN dt_temuan_patrol.status = 4 THEN 1 ELSE 0 END) AS cancel_count,
            COUNT(*) AS total
        ", false)
            ->join("departement departement", 'departement.id_departement_henk = dt_temuan_patrol.id_departement', 'left')
            ->join("section section", 'section.id_section_henk = dt_temuan_patrol.id_section', 'left')
            ->where("CONVERT(date, dt_temuan_patrol.tanggal_patrol, 106) >=", $start)
            ->where("CONVERT(date, dt_temuan_patrol.tanggal_patrol, 106) <=", $end)

            ->groupBy([
                'dt_temuan_patrol.id_section',
                'departement.departement',
                'section.section'
            ])
            ->get()
            ->getResultArray();
    }


    public function filterDept($idDept)
    {

        return $this->db->table('dt_temuan_patrol')
            ->select("
    dt_temuan_patrol.id_departement,
    dt_temuan_patrol.status,
    MONTH(tanggal_patrol) AS bulan,
    SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_total,
    SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_total,
    SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_total,
    SUM(CASE WHEN dt_temuan_patrol.status = 4 THEN 1 ELSE 0 END) AS cancel_total,
    
     COUNT(*) AS total
    ")
            ->join("section section", 'section.id_section_henk = dt_temuan_patrol.id_section', 'left')
            ->where('dt_temuan_patrol.id_departement', $idDept)

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

        return $this->db->table('dt_temuan_patrol')
            ->select("
     SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS open_total,
            SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS progress_total,
            SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS close_total,
            SUM(CASE WHEN dt_temuan_patrol.status = 4 THEN 1 ELSE 0 END) AS cancel_total,
    ")
            ->join("section section", 'section.id_section_henk = dt_temuan_patrol.id_section', 'left')
            ->where('dt_temuan_patrol.id_departement', $idDept)

            ->get()->getRowArray();
    }

    public function chartByYearNow2()
    {
        // $year = date('Y');

        return $this->db->table('dt_temuan_patrol')
            ->select("status, COUNT(*) AS total")
            // ->where('YEAR(tanggal_patrol)', $year)
            ->groupBy('status')
            ->get()
            ->getResultArray();
    }
    public function totalTemuanByArea()
    {

        return $this->db->table('dt_temuan_patrol')
            ->select("
            dt_temuan_patrol.id_section,
            d.departement AS nama_departemen,
            s.section AS nama_section,

            -- COUNT per status numeric
            SUM(CASE WHEN dt_temuan_patrol.status = 3 THEN 1 ELSE 0 END) AS total_open,
            SUM(CASE WHEN dt_temuan_patrol.status = 2 THEN 1 ELSE 0 END) AS total_progress,
            SUM(CASE WHEN dt_temuan_patrol.status = 1 THEN 1 ELSE 0 END) AS total_close,
            SUM(CASE WHEN dt_temuan_patrol.status = 4 THEN 1 ELSE 0 END) AS total_cancel,

            COUNT(dt_temuan_patrol.id_temuan_patrol) AS total_temuan
        ")
            ->join("departement d", 'd.id_departement_henk = dt_temuan_patrol.id_departement', 'left')
            ->join("section s", 's.id_section_henk = dt_temuan_patrol.id_section', 'left')

            ->groupBy([
                'dt_temuan_patrol.id_section',
                'd.departement',
                's.section'
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
            ->join("departement d", 'd.id_departement = ds.id_dept', 'left')
            ->join("section s", 's.id_section = ds.id_section', 'left')
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
        s.section AS section_name,
        d.departement AS departement_name,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dt_schedule.tanggal_actual
    ")
            ->join("departement d", 'd.id_departement = dt_schedule.id_dept', 'left')
            ->join("section s", 's.id_section = dt_schedule.id_section', 'left')
            ->where('dt_schedule.id_dept !=', $id_dept)
            ->where('dt_schedule.id_section !=', $id_section)
            ->get()
            ->getResultArray();
    }
    public function get_Alldata_scheduleByUser()
    {
        return $this->db->table('dt_schedule')
            ->select("
            s.section,
            d.departement,
            dt_schedule.tanggal_patrol,
            dt_schedule.id_schedule,
            dt_schedule.tanggal_actual,
            STRING_AGG(m.nama, ', ') AS nama_auditor
        ")
            ->join("departement d", 'd.id_departement = dt_schedule.id_dept', 'left')
            ->join("section s", 's.id_section = dt_schedule.id_section', 'left')
            ->join('dt_daftar_hadir', 'dt_daftar_hadir.id_schedule = dt_schedule.id_schedule', 'left')
            ->join("{$this->db2}.dbo.master_data_karyawan m", 'm.npk = dt_daftar_hadir.npk', 'left')

            ->where('dt_daftar_hadir.type_data', 'plan')
            ->groupBy("
            s.section,
            d.departement,
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
        s.section,
        d.departement,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dt_schedule.tanggal_actual,
        m.nama AS nama_auditor
    ")
            ->join("departement d", 'd.id_departement = dt_schedule.id_dept', 'left')
            ->join("section s", 's.id_section = dt_schedule.id_section', 'left')
            // ->join('users', 'users.id = dt_schedule.id_auditor', 'left')
            ->join('dt_daftar_hadir', 'dt_daftar_hadir.id_schedule = dt_schedule.id_schedule', 'left')
            ->join("{$this->db2}.dbo.master_data_karyawan m", 'm.npk = dt_daftar_hadir.npk', 'left')
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
        s.section,
        d.departement,
        dt_schedule.tanggal_patrol,
        dt_schedule.id_schedule,
        dh.npk,
        dh.signed_at,
        dh.keterangan,
        dh.role,
        m.nama,
        dh.type_data
    ");

        $builder->join('dt_schedule', 'dh.id_schedule = dt_schedule.id_schedule', 'left')
            ->join("departement d", 'd.id_departement = dt_schedule.id_dept', 'left')
            ->join("section s", 's.id_section = dt_schedule.id_section', 'left')
            ->join("{$this->db2}.dbo.master_data_karyawan m", 'm.npk = dh.npk', 'left')
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
        d.departement AS departement_name,
        dt_schedule.id_section,
        s.section AS section_name,
        ks.nama AS kepala_seksi,
        kd.nama AS kepala_departement,
        COALESCE(ks.nama, kd.nama) AS nama_penanggung_jawab
    ")
            ->join(
                "departement d",
                'd.id_departement = dt_schedule.id_dept',
                'left'
            )
            ->join(
                "section s",
                's.id_section = dt_schedule.id_section',
                'left'
            )
            # Kepala Seksi
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS ks",
                "ks.id_section = dt_schedule.id_section
         AND ks.jabatan = 'Kepala Seksi'",
                'left'
            )
            # Kepala Departemen
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS kd",
                "kd.id_departement = dt_schedule.id_dept
         AND kd.jabatan = 'Kepala Departemen'",
                'left'
            )
            ->where('dt_schedule.id_schedule', $schedule_id)
            ->get()
            ->getRowArray();
    }
    public function get_deptSection_byIdSectDept($id_section_henk, $id_dept_henk = null)
    {
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

            // Kepala Seksi: pakai ID HENK
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS ks",
                "ks.id_section = " . (int)$id_section_henk . " AND ks.jabatan = 'Kepala Seksi'",
                "left",
                false
            )

            // Kepala Departemen (fallback): pakai ID HENK
            ->join(
                "{$this->db2}.dbo.master_data_karyawan AS kd",
                "kd.id_departement = " . (int)$id_dept_henk . " AND kd.jabatan = 'Kepala Departemen'",
                "left",
                false
            )
            ->where("d.id_departement_henk", $id_dept_henk);

        // opsional: kalau mau validasi bahwa section tsb memang berada di dept HENK yang dipilih
        // ini butuh mapping dept_henk dari tabel departement:
        // ->where("d.id_departement_henk", $id_dept_henk);

        return $builder->get()->getRowArray();
    }
}
