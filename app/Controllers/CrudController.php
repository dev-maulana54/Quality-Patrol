<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;
use CodeIgniter\HTTP\ResponseInterface;

class CrudController extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {
        # Inisialisasi Model
        $this->dataPatrol = new Model_data_patrol();
    }
    private function formatTanggalPatrol($tanggal)
    {
        if (!$tanggal) return null;

        try {
            $dt = new \DateTime($tanggal);

            // format: 09 Apr 2026
            return $dt->format('d M Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function authLogin() # Function untuk proses login
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        if (! $username || ! $password) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'msg' => 'Username dan password wajib diisi',]);
        }
        # 1) login ke API di function request_login_api
        $api = $this->request_login_api($username, $password);
        if (! $api['ok']) {
            return $this->response->setStatusCode(401)->setJSON($api);
        }
        # 2) ambil NPK dari API 
        $npk = $api['npk'] ?? null;
        if (! $npk) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'msg' => 'Login API sukses tapi NPK tidak ditemukan di response', 'api' => $api['api_user'] ?? null,]);
        }
        # 3) cari user Admin berdasarkan NPK
        $userLocal = $this->dataPatrol
            ->db
            ->table('users')
            ->where('npk', $npk)
            ->get()
            ->getRowArray();

        # default session
        $sessionData = [
            'isLoggedIn' => true,
            'npk'        => $npk,
            'role'       => 'user', # default
        ];

        # Set Role Administrator jika user ditemukan dan admin
        if ($userLocal !== null && $userLocal['role'] == 1) {
            $sessionData['role'] = 'Administrator';
        }

        # set session (selalu)
        session()->set($sessionData);



        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Login berhasil',
            'user' => $userLocal,
            'npk' => $npk,
        ]);
    }



    public function setActiveRole()
    {
        $roleId = (int) $this->request->getPost('role_id');
        $pending = session()->get('pending_login');

        if (! $pending || empty($pending['npk'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok'  => false,
                'msg' => 'Session pending login tidak ditemukan. Silakan login ulang.',
            ]);
        }

        if (! in_array($roleId, [1, 2, 3], true)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok'  => false,
                'msg' => 'Role tidak valid.',
            ]);
        }

        $npk = $pending['npk'];

        # validasi bahwa NPK ini memang punya role tsb (dari tabel users)
        $userRow = $this->dataPatrol->db->table('users')
            ->select('id, npk, role')
            ->where('npk', $npk)
            ->where('role', $roleId)
            ->get()
            ->getRowArray();

        if (! $userRow) {
            return $this->response->setStatusCode(403)->setJSON([
                'ok'  => false,
                'msg' => 'Role yang dipilih tidak dimiliki oleh user ini.',
            ]);
        }

        $roleLabel = [1 => 'Admin', 2 => 'Auditor', 3 => 'Auditee'];

        # set session final
        session()->set([
            'isLoggedIn'  => true,
            'npk'         => $npk,
            'user_row_id' => (int) $userRow['id'],
            'active_role' => $roleId,
            'role_name'   => $roleLabel[$roleId] ?? ('Role ' . $roleId),
        ]);

        session()->remove('pending_login');

        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Role aktif berhasil diset',
            'redirect' => base_url('summary'),
        ]);
    }


    private function request_login_api(string $username, string $password): array
    {
        $url = 'https://portal3.incoe.astra.co.id/production_control_v2/public/api/login';
        $ch  = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'username' => $username,
                'password' => $password,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_TIMEOUT        => 20,

            # sementara (karena issuer cert)
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,

            CURLOPT_USERAGENT      => 'PostmanRuntime/7.36.0',
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
            ],
        ]);

        $raw = curl_exec($ch);

        if ($raw === false) {
            $err = curl_error($ch);
            $no  = curl_errno($ch);
            curl_close($ch);

            return [
                'ok'    => false,
                'msg'   => 'Gagal menghubungi API login',
                'error' => "{$no} : {$err}",
            ];
        }

        $status     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body       = substr($raw, $headerSize);

        curl_close($ch);

        $decoded = json_decode($body, true);

        if ($status === 200 && json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
            # Pastikan flag sukses dari API
            if (!empty($decoded['is_login']) && !empty($decoded['npk'])) {
                return [
                    'ok'       => true,
                    'msg'      => 'Login API berhasil',
                    'npk'      => $decoded['npk'],
                    'api_user' => $decoded, # simpan full data kalau perlu
                ];
            }

            return [
                'ok'       => false,
                'msg'      => 'API mengembalikan JSON tapi login tidak valid',
                'api_user' => $decoded,
            ];
        }

        return [
            'ok'        => false,
            'msg'       => 'Username tidak di temukan!',
            'status'    => $status,
            'body_raw'  => $body,
            'json_error' => json_last_error_msg(),
        ];
    }


    public function sendData()
    {

        # Ambil data dari POST
        $namaDept = $this->request->getPost('nama_dept');
        $keterangan = $this->request->getPost('keterangan');

        # controller untuk Tambah Departement
        if ($keterangan == 'get_dept') {

            $idDept = $this->request->getPost('id_dept');
            $deptData = $this->dataPatrol->getDept_byId($idDept);
            $loop_seksi = $this->dataPatrol->getSeksi_byDeptId($idDept);

            $html = '';
            foreach ($loop_seksi as $index => $seksi) {
                $html .= '<tr>
                    <th scope="row">' . ($index + 1) . '</th>
                    <td>' . $seksi['nama_seksi'] . '</td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapus_seksi" data-id="' . $seksi['id_seksi'] . '"  data-idDept="' . $idDept . '">Hapus</button></td>
                </tr>';
            }
            $deptData['html_seksi'] = $html;
            return $this->response->setJSON($deptData);
        } else if ($keterangan == 'get_dept_seksi') { # Ambil Departemen dan Seksi berdasarkan NPK
            $npk_user = $this->request->getPost('npk');
            $loop_seksi = $this->dataPatrol->getDeptSectionbyId($npk_user);
            $nama_dept_user = $loop_seksi['departement'];
            $nama_seksi_user = $loop_seksi['section'];

            return $this->response->setJSON(['nama_dept_user' => $nama_dept_user, 'nama_seksi_user' => $nama_seksi_user]);
        } else if ($keterangan == 'get_seksi_by_dept') { # Ambil Seksi berdasarkan Departemen
            $deptId = $this->request->getPost('id_dept');
            $loop_seksi = $this->dataPatrol->getSeksi_byDeptId($deptId);

            $options = '';
            foreach ($loop_seksi as $seksi) {
                $options .= '<option value="' . $seksi['id_section'] . '" data-section="' . $seksi['section'] . '">' . $seksi['section'] . '</option>';
            }

            return $this->response->setJSON(['options' => $options]);
        } else if ($keterangan == 'tambah_user') { # Controller untuk Tambah User Administrator
            $npk = $this->request->getPost('npk_user');


            $role = $this->request->getPost('role_user');
            # cek apakah npk sudah ada di tabel users

            $cekNPK = $this->dataPatrol->db->table('users')->where('npk', $npk)->get()->getRowArray();
            if ($cekNPK) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'NPK sudah terdaftar']);
            }

            $data = [
                'npk' => $npk,
                'created_at' => date('Y-m-d H:i:s'),
                'role' => $role,
            ];

            # Simpan data user ke database
            $this->dataPatrol->db->table('users')->insert($data);

            return $this->response->setJSON(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
        } else if ($keterangan == 'find_auditee_by_id_seksi_dept') {
            # ambil nama seksi yang jabatannya Kepala Seksi berdasarkan id_seksi di master_data_karyawan dan ambil
            $id_seksi = $this->request->getPost('id_seksi');
            $id_dept = $this->request->getPost('id_dept');
            $id_dept_henk = $this->dataPatrol->db->table('departement')->where('id_departement', $id_dept)->get()->getRowArray();
            $id_sect_henk = $this->dataPatrol->db->table('section')->where('id_section', $id_seksi)->get()->getRowArray();
            $auditee = $this->dataPatrol->get_deptSection_byIdSectDept($id_sect_henk['id_section_henk'], $id_dept_henk['id_departement_henk']);


            # Cek apakah data ada atau null
            if ($auditee && isset($auditee['nama_penanggung_jawab'])) {
                $nama_auditee = $auditee['nama_penanggung_jawab'];
            } else {
                $nama_auditee = 'Nama Atasan tidak ada';
            }
            return $this->response->setJSON(['auditee' => $nama_auditee]);
        } else if ($keterangan == 'tambah_temuan_patrol') {

            $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));

            // AREA PATROL
            $id_seksi = $this->request->getPost('seksiId');
            $id_dept = $this->request->getPost('deptId');
            $id_dept_henk = $this->dataPatrol->db->table('departement')->where('id_departement', $id_dept)->get()->getRowArray();
            $id_sect_henk = $this->dataPatrol->db->table('section')->where('id_section', $id_seksi)->get()->getRowArray();

            $idSectionFinal = (!empty($id_sect_henk) && $id_sect_henk['id_section_henk'] != 0)
                ? $id_sect_henk['id_section_henk']
                : $id_seksi;


            // Ambil rekap temuan dari localStorage (JSON)
            $rekapJson  = $this->request->getPost('rekap_temuan');
            $rekapList  = json_decode($rekapJson, true) ?? [];

            // Evidence files[]
            $allFiles = $this->request->getFiles();
            $uploadedFiles = $allFiles['evidence_files'] ?? [];

            // Folder evidence: public/uploads/findings_evidence/
            $targetDir = FCPATH . 'uploads/findings_evidence/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $batchData = [];
            // ambil tanggal patrol dari form
            $tanggalRaw = $this->request->getPost('tanggal_patrol');

            // ubah format jadi: 09 Apr 2026
            $tanggalPatrol = $this->formatTanggalPatrol($tanggalRaw);

            if ($tanggalPatrol === null) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tanggal patrol tidak valid.'
                ])->setStatusCode(400);
            }
            foreach ($rekapList as $item) {

                $savedFileName = null;
                $fileIndex = $item['file_index'] ?? null;

                // ===============================
                // ✅ Query id_dept_henk dari pic_action_dept_id
                // ===============================
                $idDeptHenk_pic = null;
                $idsectHenk_pic = null;
                if (!empty($item['pic_action_dept_id']) || !empty($item['pic_action_section_id'])) {
                    $deptData = $this->dataPatrol->db->table('departement')->where('id_departement', $item['pic_action_dept_id'])->get()->getRowArray();
                    $sectData = $this->dataPatrol->db->table('section')->where('id_section', $item['pic_action_section_id'])->get()->getRowArray();
                    $idDeptHenk_pic = $deptData['id_departement_henk'] ?? null;
                    $idsectHenk_pic = (!empty($sectData) && !empty($sectData['id_section_henk']) && $sectData['id_section_henk'] != 0)
                        ? $sectData['id_section_henk']
                        : $item['pic_action_section_id'];
                }

                // ===============================
                // ✅ Upload evidence jika ada
                // ===============================
                if ($fileIndex !== null && isset($uploadedFiles[$fileIndex])) {

                    $file = $uploadedFiles[$fileIndex];

                    if ($file && $file->isValid() && !$file->hasMoved()) {

                        $ext = $file->getClientExtension();

                        $newName = 'evidence_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5));
                        if ($ext) {
                            $newName .= '.' . $ext;
                        }

                        $file->move($targetDir, $newName);
                        $savedFileName = $newName;
                    }
                }

                // ===============================
                // ✅ Data insert batch
                // ===============================
                $batchData[] = [
                    'tanggal_patrol'              => $tanggalPatrol,
                    'id_auditor'                  => session()->get('npk'),
                    'nama_auditor'                => $getdata_user['nama'],
                    'nama_auditee'                => $this->request->getPost('nama_auditee'),
                    'id_departement'              => $id_dept_henk['id_departement_henk'],
                    'id_section'                  => $idSectionFinal,
                    'deskripsi_temuan'            => $item['deskripsi_temuan'] ?? null,
                    'pic_action_departement_id'   => $idDeptHenk_pic,
                    'pic_action_section_id'       => $idsectHenk_pic,
                    'due_date'                    => 0,
                    'status'                      => 3,
                    // 'id_dt_schedule'              => $this->request->getPost('id_schedule'),

                    // Evidence file
                    'evidence_file'               => $savedFileName,

                    // ✅ Approval default (baru)
                    'status_approval'             => 1,
                ];
            }

            // ===============================
            // ✅ Insert semua temuan sekaligus
            // ===============================
            $this->dataPatrol->db
                ->table('dt_temuan_patrol')
                ->insertBatch($batchData);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Temuan patrol berhasil ditambahkan',
            ]);
        } else if ($keterangan == 'get_temuan_by_id') { # function untuk mengambil data temuan patrol based on id_temuan
            $id_temuan = $this->request->getPost('id_temuan');
            # ambil data temuan patrol berdasarkan id_temuan_patrol dan join tabel departement dan section
            $temuan = $this->dataPatrol->db->table('dt_temuan_patrol')
                ->select('
        dt_temuan_patrol.*,
        d.departement AS departement_name,
        s.section AS section_name,
        pic_dept.departement AS pic_departement_name,
        pic_sec.section AS pic_section_name
    ')
                ->join('departement d', 'dt_temuan_patrol.id_departement = d.id_departement_henk', 'left')
                ->join('section s', 'dt_temuan_patrol.id_section = s.id_section_henk', 'left')
                ->join('departement AS pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement_henk', 'left')
                ->join('section AS pic_sec', 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section_henk', 'left')
                ->where('dt_temuan_patrol.id_temuan_patrol', $id_temuan)
                ->get()
                ->getRowArray();


            # PIC Action 
            $pic_action = $this->dataPatrol->get_Alldata_dept();
            $nama_pic = '';

            foreach ($pic_action as $pa) {
                $isSelected5 = $temuan['pic_action_departement_id'] == $pa['id_departement_henk'] ? 'selected' : '';
                $nama_pic .= "<option value='{$pa['id_departement_henk']}' {$isSelected5}>{$pa['departement']}</option>";
            }
            # Data User Login dengan Role Auditor
            $data_auditor = $this->dataPatrol->get_dataAllAuditor();
            $nama_auditor = '';

            foreach ($data_auditor as $dk) {
                $isSelected = $temuan['id_auditor'] == $dk['npk'] ? 'selected' : '';
                $nama_auditor .= "<option value='{$dk['npk']}' {$isSelected}>{$dk['nama']}</option>";
            }
            # Data User Login dengan Role Auditee
            $data_auditee = $this->dataPatrol->get_dataAllAuditee();
            $nama_auditee = '';

            foreach ($data_auditee as $da) {
                $isSelected2 = $temuan['nama_auditee'] == $da['nama'] ? 'selected' : '';
                $nama_auditee .= "<option value='{$da['npk']}' {$isSelected2}>{$da['nama']}</option>";
            }

            # Data Nama Section
            $list_section = $this->dataPatrol->get_Alldata_seksi();
            $section = '';
            foreach ($list_section as $ls) {
                $isSelected3 = $temuan['id_section'] == $ls['id_section'] ? 'selected' : '';
                $section .=  "<option value='{$ls['id_section']}' {$isSelected3}>{$ls['section']}</option>";
            }


            if (session()->get('role') == 'Administrator') {

                $kirim = [
                    'id_auditor' => $temuan['id_auditor'],
                    'id_temuan_patrol' => $temuan['id_temuan_patrol'],
                    'tanggal_patrol' => $temuan['tanggal_patrol'],
                    'nama_auditor' => $nama_auditor,
                    'nama_auditee' => $nama_auditee,
                    'section_name' => $section,
                    'deskripsi_temuan' => $temuan['deskripsi_temuan'],
                    'analisa_penyebab' => $temuan['analisa_penyebab'],
                    'action' => $temuan['action'],
                    'pic_section_name' => $nama_pic,
                    'nama_file' => $temuan['nama_file'],
                    'finding_evidence' => $temuan['evidence_file'],
                    'status_temuan' => $temuan['status'],
                    'due_date' => $temuan['due_date'],
                    'keterangan_cancel' => $temuan['keterangan_cancel'],
                    'keterangan_auditor' => $temuan['keterangan_auditor']
                ];
                return $this->response->setJSON(['temuan' => $kirim]);
            } else if (session()->get('role') == 'user') {
                $kirim = [
                    'id_auditor' => $temuan['id_auditor'],
                    'id_temuan_patrol' => $temuan['id_temuan_patrol'],
                    'tanggal_patrol' => $temuan['tanggal_patrol'],
                    'nama_auditor' => $temuan['nama_auditor'],
                    'nama_auditee' => $temuan['nama_auditee'],
                    'section_name' => $temuan['section_name'],
                    'deskripsi_temuan' => $temuan['deskripsi_temuan'],
                    'analisa_penyebab' => $temuan['analisa_penyebab'],
                    'id_section' => $temuan['id_section'],
                    'id_departement' => $temuan['id_departement'],
                    'action' => $temuan['action'],
                    'pic_section_name' => $nama_pic,
                    'nama_file' => $temuan['nama_file'],
                    'due_date' => $temuan['due_date'],
                    'status_temuan' => $temuan['status'],
                    'finding_evidence' => $temuan['evidence_file'],
                    'keterangan_cancel' => $temuan['keterangan_cancel'],
                    'keterangan_auditor' => $temuan['keterangan_auditor']
                ];
                return $this->response->setJSON(['temuan' => $kirim]);
            } else {
                return $this->response->setJSON(['temuan' => $temuan]);
            }
        } else if ($keterangan == 'get_temuan_auditee_by_id') {
            $id_temuan = $this->request->getPost('id_temuan');
            # ambil data temuan patrol berdasarkan id_temuan_patrol dan join tabel departement dan section
            $temuan = $this->dataPatrol->db->table('dt_temuan_patrol')
                ->select('
        dt_temuan_patrol.*,
        d.departement AS departement_name,
        s.section AS section_name,
        pic_dept.departement AS pic_departement_name,
        pic_sec.section AS pic_section_name
    ')
                ->join('departement d', 'dt_temuan_patrol.id_departement = d.id_departement_henk', 'left')
                ->join('section s', 'dt_temuan_patrol.id_section = s.id_section_henk', 'left')
                ->join('departement AS pic_dept', 'dt_temuan_patrol.pic_action_departement_id = pic_dept.id_departement_henk', 'left')
                ->join('section AS pic_sec', 'dt_temuan_patrol.pic_action_section_id = pic_sec.id_section_henk', 'left')
                ->where('dt_temuan_patrol.id_temuan_patrol', $id_temuan)
                ->get()
                ->getRowArray();


            #PIC Action 
            $pic_action = $this->dataPatrol->get_Alldata_dept();
            $nama_pic = '';

            foreach ($pic_action as $pa) {
                $isSelected5 = $temuan['pic_action_departement_id'] == $pa['id_departement_henk'] ? 'selected' : '';
                $nama_pic .= "<option value='{$pa['id_departement_henk']}' {$isSelected5}>{$pa['departement']}</option>";
            }
            # Data User Login dengan Role Auditor
            $data_auditor = $this->dataPatrol->get_dataAllAuditor();
            $nama_auditor = '';

            foreach ($data_auditor as $dk) {
                $isSelected = $temuan['id_auditor'] == $dk['npk'] ? 'selected' : '';
                $nama_auditor .= "<option value='{$dk['npk']}' {$isSelected}>{$dk['nama']}</option>";
            }
            # Data User Login dengan Role Auditee
            $data_auditee = $this->dataPatrol->get_dataAllAuditee();
            $nama_auditee = '';

            foreach ($data_auditee as $da) {
                $isSelected2 = $temuan['nama_auditee'] == $da['nama'] ? 'selected' : '';
                $nama_auditee .= "<option value='{$da['npk']}' {$isSelected2}>{$da['nama']}</option>";
            }

            #data Nama Section
            $list_section = $this->dataPatrol->get_Alldata_seksi();
            $section = '';
            foreach ($list_section as $ls) {
                $isSelected3 = $temuan['id_section'] == $ls['id_section'] ? 'selected' : '';
                $section .=  "<option value='{$ls['id_section']}' {$isSelected3}>{$ls['section']}</option>";
            }



            $kirim = [
                'id_auditor' => $temuan['id_auditor'],
                'id_temuan_patrol' => $temuan['id_temuan_patrol'],
                'tanggal_patrol' => $temuan['tanggal_patrol'],
                'nama_auditor' => $temuan['nama_auditor'],
                'nama_auditee' => $temuan['nama_auditee'],
                'section_name' => $temuan['section_name'],
                'deskripsi_temuan' => $temuan['deskripsi_temuan'],
                'analisa_penyebab' => $temuan['analisa_penyebab'],
                'id_section' => $temuan['id_section'],
                'id_departement' => $temuan['id_departement'],
                'action' => $temuan['action'],
                'pic_section_name' => $nama_pic,
                'nama_file' => $temuan['nama_file'],
                'due_date' => $temuan['due_date'],
                'status_temuan' => $temuan['status'],
                'finding_evidence' => $temuan['evidence_file'],
                'keterangan_cancel' => $temuan['keterangan_cancel'],
                'keterangan_auditor' => $temuan['keterangan_auditor']
            ];
            return $this->response->setJSON(['temuan' => $kirim]);
        } else if ($keterangan == 'update_temuan_patrol') {
            $id_temuan = $this->request->getPost('id_temuan');
            $file      = $this->request->getFile('file');
            $npk_auditor = $this->request->getPost('npk_auditor');
            $data_update = []; # mulai dari array kosong


            # hanya admin atau auditor yang ditunjuk yang bisa update temuan (termasuk upload file)
            if (session()->get('role') == 'Administrator' || $npk_auditor == session()->get('npk')) {
                # hanya admin yang bisa update semua field
                if (session()->get('role') == 'Administrator') {
                    # update auditor jika npk_auditor tidak kosong
                    if ($npk_auditor != '') {
                        $nama_auditor = $this->dataPatrol->getdata_karyawan_byUsername($npk_auditor);

                        $data_update['id_auditor'] = $npk_auditor;
                        $data_update['nama_auditor'] = $nama_auditor['nama'];
                    }

                    $id_section_pcaudit = $this->request->getPost('area_prosesaudit');
                    $id_dept = $this->dataPatrol->tb_section($id_section_pcaudit);
                    # jika area proses audit tidak kosong
                    if ($id_section_pcaudit != '') {
                        #ambil nama auditee berdasarkan id_section dan id_departement
                        $nama_auditee = $this->dataPatrol->get_deptSection_byIdSectDept($id_section_pcaudit, $id_dept['id_departement_henk']);
                        #ambil nama auditee di master data karyawan berdasarkan npk auditee yang dipilih
                        $validasi_auditee_sama = $this->dataPatrol->getdata_karyawan_byUsername($this->request->getPost('npk_auditee'));
                        #ambil nama auditee di temuan patrol berdasarkan id_temuan_patrol
                        $get_auditee_temuan = $this->dataPatrol->db->table('dt_temuan_patrol')->where('id_temuan_patrol', $id_temuan)->get()->getRowArray();
                        /* cek jika nama auditee yang dipilih di master data karyawan tidak sama dengan nama auditee di temuan patrol, 
                        maka update nama auditee dengan nama auditee yang dipilih di master data karyawan */

                        if ($validasi_auditee_sama['nama'] != $get_auditee_temuan['nama_auditee']) {
                            $data_update['nama_auditee'] = $validasi_auditee_sama['nama'];
                        } else {
                            /* RULES :
                            1. Jika nama auditee master karyawan sama dengan nama auditee di temuan patrol
                            2. Dan jika Area Proses Audit yang di pilih tidak sama dengan area proses di temuan patrol
                            3. Maka update nama auditee dengan nama penanggung jawab di master data karyawan

                            */
                            if ($id_section_pcaudit != $get_auditee_temuan['id_section']) {
                                $data_update['nama_auditee'] = $nama_auditee['nama_penanggung_jawab'];
                            }
                            // $data_update['nama_auditee'] = $nama_auditee['nama_penanggung_jawab'];
                        }
                        $data_update['id_section'] = (int)$id_section_pcaudit;
                        $data_update['id_departement'] = $id_dept['id_departement_henk'];
                    }
                    $get_section_dept = $this->dataPatrol->getSection_andDeptByIDNEW($this->request->getPost('area_pic_action'));
                    $data_update['pic_action_section_id'] = (int)$this->request->getPost('area_pic_action');
                    $data_update['pic_action_departement_id'] = $get_section_dept['id_departement_henk'];
                    $data_update['deskripsi_temuan'] = $this->request->getPost('deskripsi_temuan');
                    $data_update['analisa_penyebab'] = $this->request->getPost('analisa_penyebab');
                    $data_update['action']           = $this->request->getPost('action');
                    $tanggal_patrol = $this->request->getPost('tanggal_patrol');
                    $due_date       = $this->request->getPost('due_date');
                    if (!empty($tanggal_patrol)) {
                        $data_update['tanggal_patrol'] = $tanggal_patrol;
                    }

                    if (!empty($due_date)) {
                        $data_update['due_date'] = $due_date;
                    }
                    # ==== HANDLE FILE ====
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $originalName = $file->getClientName();
                        $cleanName    = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                        $timestamp    = time();
                        $newName      = $timestamp . '_' . $cleanName;

                        $target = FCPATH . 'assets/uploads/';
                        if (!is_dir($target)) {
                            mkdir($target, 0775, true);
                        }

                        $file->move($target, $newName);

                        $data_update['nama_file'] = $newName;
                    }
                }
                $status = $this->request->getPost('status');
                if ($status !== null && $status !== '') {   # biar '0' juga bisa
                    $data_update['status'] = $status;
                    $data_update['keterangan_cancel'] = $this->request->getPost('keterangan_cancel') ?: null;
                    $data_update['deskripsi_temuan'] = $this->request->getPost('deskripsi_temuan');
                    $data_update['keterangan_auditor'] =  $this->request->getPost('keterangan_auditor');
                } else {

                    $data_update['deskripsi_temuan'] = $this->request->getPost('deskripsi_temuan');
                }
            } else {

                # ==== HANDLE FILE ====
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $originalName = $file->getClientName();
                    $cleanName    = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                    $timestamp    = time();
                    $newName      = $timestamp . '_' . $cleanName;

                    $target = FCPATH . 'assets/uploads/';
                    if (!is_dir($target)) {
                        mkdir($target, 0775, true);
                    }

                    $file->move($target, $newName);

                    $data_update['nama_file'] = $newName;
                }

                $get_section_dept = $this->dataPatrol->getSection_andDeptByIDNEW($this->request->getPost('pic_action'));
                $data_update['pic_action_section_id'] = (int)$this->request->getPost('pic_action');
                $data_update['pic_action_departement_id'] = $get_section_dept['id_departement_henk'];

                $data_update['status'] = 2; # langsung simpan
                $data_update['deskripsi_temuan'] = $this->request->getPost('deskripsi_temuan');
                $data_update['analisa_penyebab'] = $this->request->getPost('analisa_penyebab');
                $data_update['action']           = $this->request->getPost('action');
                $tanggal_patrol = $this->request->getPost('tanggal_patrol');
                $due_date       = $this->request->getPost('due_date');



                if (!empty($tanggal_patrol)) {
                    $data_update['tanggal_patrol'] = $tanggal_patrol;
                }

                if (!empty($due_date)) {
                    $data_update['due_date'] = $due_date;
                }
            }

            // ==== FIELD LAIN ====


            $this->dataPatrol->db->table('dt_temuan_patrol')
                ->where('id_temuan_patrol', $id_temuan)
                ->update($data_update);
            return $this->response->setJSON([
                'status'  => 'success',
                'data_update' => $data_update,
                'message' => 'Data temuan patrol berhasil diperbarui'
            ]);
        } else if ($keterangan == 'update_temuan_patrol_auditee') { # function update temuan patrol by Auditee
            $id_temuan = $this->request->getPost('id_temuan');
            $file      = $this->request->getFile('file');
            $npk_auditor = $this->request->getPost('npk_auditor');
            $data_update = []; # mulai dari array kosong



            // ==== HANDLE FILE ====
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $originalName = $file->getClientName();
                $cleanName    = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                $timestamp    = time();
                $newName      = $timestamp . '_' . $cleanName;

                $target = FCPATH . 'assets/uploads/';
                if (!is_dir($target)) {
                    mkdir($target, 0775, true);
                }

                $file->move($target, $newName);

                $data_update['nama_file'] = $newName;
            }

            // $get_section_dept = $this->dataPatrol->getDataDept_basedOnID($this->request->getPost('pic_action'));
            // $data_update['pic_action_section_id'] = $this->request->getPost('pic_action');
            $data_update['pic_action_departement_id'] = $this->request->getPost('pic_action');
            $data_update['status'] = 2; // langsung simpan
            $deskripsi_temuan = $this->request->getPost('deskripsi_temuan');
            $analisa_penyebab = $this->request->getPost('analisa_penyebab');
            $action = $this->request->getPost('action');

            $tanggal_patrol = $this->request->getPost('tanggal_patrol');
            $due_date       = $this->request->getPost('due_date');


            if (!empty($deskripsi_temuan)) {
                $data_update['deskripsi_temuan'] = $deskripsi_temuan;
            }
            if (!empty($analisa_penyebab)) {
                $data_update['analisa_penyebab'] = $analisa_penyebab;
            }
            if (!empty($action)) {
                $data_update['action'] = $action;
            }
            if (!empty($tanggal_patrol)) {
                $data_update['tanggal_patrol'] = $tanggal_patrol;
            }

            if (!empty($due_date)) {

                // Cek apakah sudah format d/m/Y
                $dateObj = \DateTime::createFromFormat('d/m/Y', $due_date);
                $isValidDMY = $dateObj && $dateObj->format('d/m/Y') === $due_date;

                if ($isValidDMY) {
                    // Sudah d/m/Y, langsung pakai
                    $data_update['due_date'] = $due_date;
                } else {
                    // Format lain, coba konversi pakai strtotime
                    $timestamp = strtotime($due_date);

                    if ($timestamp !== false) {
                        $data_update['due_date'] = date('d/m/Y', $timestamp);
                    } else {
                        $data_update['due_date'] = null;
                    }
                }
            }


            // ==== FIELD LAIN ====


            $this->dataPatrol->db->table('dt_temuan_patrol')
                ->where('id_temuan_patrol', $id_temuan)
                ->update($data_update);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data temuan patrol berhasil diperbarui'
            ]);
        } else if ($keterangan == 'hapus_temuan_patrol') { # function untuk menghapus data temuan patrol
            $id_temuan_patrol = $this->request->getPost('id_temuan_patrol');
            # Hapus data temuan patrol dari database
            $this->dataPatrol->db->table('dt_temuan_patrol')->where('id_temuan_patrol', $id_temuan_patrol)->delete();
        } else if ($keterangan == 'getData_filter_year') {
            $tahun = $this->request->getPost('tahun');

            // DATA PER BULAN TAHUN SEKARANG
            $dataRaw = $this->dataPatrol->Filter_chartByYearNow($tahun);
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
            // Contoh struktur:
            $response = [

                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'open' => $open,         // array numeric (12 nilai)
                'progress' => $inprogress, // array numeric (12 nilai)
                'close' => $close,       // array numeric (12 nilai)
                'cancel' => $cancel,     // array numeric (12 nilai)
                'total' => $total        // array numeric (12 nilai) -> spline
            ];

            return $this->response->setJSON($response);
        } else if ($keterangan == 'getData_filter_rangeDate') {
            $startDate = $this->request->getPost('startDate'); // format: YYYY-MM-DD
            $endDate   = $this->request->getPost('endDate');


            // ambil data dari model
            $rows = $this->dataPatrol->filterRangeDate($startDate, $endDate);

            // === contoh mengubah rows jadi array untuk Highcharts ===
            // sesuaikan dengan struktur data

            $categories = [];   // misal bulan / area
            $open       = [];
            $progress   = [];
            $close      = [];
            $cancel     = [];
            $total      = [];
            $all = [];

            foreach ($rows as $row) {
                # pakai nama area sebagai kategori
                $categories[] = $row['nama_section'];
                $all[] = $row;
                $open[]     = (int) ($row['open_count'] ?? 0);
                $progress[] = (int) ($row['progress_count'] ?? 0);
                $close[]    = (int) ($row['close_count'] ?? 0);
                $cancel[]   = (int) ($row['cancel_count'] ?? 0);
                $total[]    = (int) ($row['total'] ?? 0);
            }

            return $this->response->setJSON([
                'categories' => $categories,
                'open_count'       => $open,
                'progress_count'   => $progress,
                'close_count'      => $close,
                'cancel_count'     => $cancel,
                'total'      => $total,
            ]);
        } else if ($keterangan  == 'getData_filter_dept') {
            $dept      = $this->request->getPost('dept');
            $rows = $this->dataPatrol->filterDept($dept);
            // Siapkan array default 12 bulan
            $open = array_fill(0, 12, 0);
            $inprogress = array_fill(0, 12, 0);
            $close = array_fill(0, 12, 0);
            $cancel = array_fill(0, 12, 0);
            $total = array_fill(0, 12, 0);


            $all = [];
            foreach ($rows as $row) {
                $all[] = $row;
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

            $count_temuan = $this->dataPatrol->filterDept2($dept);

            return $this->response->setJSON([
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'open'       => $open,
                'progress'   => $inprogress,
                'close'      => $close,
                'cancel'     => $cancel,
                'open_count' => $count_temuan['open_total'],
                'progress_count' => $count_temuan['progress_total'],
                'close_count' => $count_temuan['close_total'],
                'cancel_count' => $count_temuan['cancel_total']
            ]);
        } else if ($keterangan == 'tambah_schedule_patrol') { # function untuk menambah schedule patrol
            $tanggal_patrol = $this->request->getPost('tanggal_patrol');
            $dept_id = $this->request->getPost('deptId');
            $section_id = $this->request->getPost('seksiId');
            $npk_auditor = $this->request->getPost('auditor');

            $data = [

                'id_dept' => $dept_id,
                'id_section' => $section_id,
                'tanggal_patrol' => $tanggal_patrol,
                'created_at' => date('Y-M-D h:m:s')
            ];
            # Simpan data user ke database (implementasi sesuai kebutuhan)
            $this->dataPatrol->db->table('dt_schedule')->insert($data);


            $id_schedule = $this->dataPatrol->db->insertID();

            $auditors = $npk_auditor; // array dari request

            $batch = [];
            foreach ($auditors as $npk) {
                $batch[] = [
                    'npk'         => $npk,
                    'role'        => 2,
                    'id_schedule' => $id_schedule,
                    'keterangan'  => 0,
                    'type_data' => 'plan'
                ];
            }

            $this->dataPatrol->db->table('dt_daftar_hadir')->insertBatch($batch);


            return $this->response->setJSON(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
        } else if ($keterangan == 'ambil_data_schedule') {
            $bulan = $this->request->getPost('month');

            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            $tahun = $this->request->getPost('year');

            $ambildata = $this->dataPatrol->get_dataSchedule_area($tahun, $bulan);
            $data = [];
            $simpan_data = [];
            $tanggal_plan_data = [];
            foreach ($ambildata as $ad) {
                $area = $ad['section'];
                $tanggal_patrol = $ad['tanggal_patrol'];
                $tanggal_actual = isset($ad['tanggal_actual']) ? $ad['tanggal_actual'] : null;
                $id_schedule = $ad['id_schedule'];
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
                            'id_schedule' => $id_schedule   // ✅ tambahin ini
                        ];
                    }
                }
            }

            return $this->response->setJSON([
                'schedule_data' => $tanggal_plan_data,
            ]);
        } else if ($keterangan == 'get_deptSection_bySchedule') {
            $scheduleid = $this->request->getPost('scheduleid');
            $get_data = $this->dataPatrol->get_deptSection_bySchedule($scheduleid);


            $data = [
                'id_departement' => $get_data['id_dept'],
                'departement' => $get_data['departement_name'],
                'id_section' => $get_data['id_section'],
                'section'     => $get_data['section_name'],
                'nama'        => $get_data['nama_penanggung_jawab'] ?? 'Nama Atasan tidak ada',
            ];


            return $this->response->setJSON($data);
        } else if ($keterangan == 'get_data_schedule') { # function untuk mengambil data schedule patrol berdasarkan id_schedule
            $id_schedule = $this->request->getPost('id_schedule');
            $type        = $this->request->getPost('type'); // 'plan' atau 'actual'

            // ambil data plan & actual untuk role 2
            $rows = $this->dataPatrol->db->table('dt_daftar_hadir')
                ->where('id_schedule', $id_schedule)
                ->where('role', 2)
                ->whereIn('type_data', ['plan', 'actual'])
                ->get()
                ->getResultArray();

            // pisahkan plan & actual
            $planRows   = [];
            $actualRows = [];
            $tanggal = $this->dataPatrol->db->table('dt_schedule')
                ->select('tanggal_patrol, tanggal_actual')
                ->where('id_schedule', $id_schedule)
                ->get()
                ->getRowArray();
            foreach ($rows as $r) {
                if ($r['type_data'] === 'plan') {
                    $planRows[] = $r;      // plan bisa 1 atau lebih
                } elseif ($r['type_data'] === 'actual') {
                    $actualRows[] = $r;    // actual bisa 1 atau lebih
                }
            }


            $getNamaTextByNpkList = function (array $npkList) {
                $npkList = array_values(array_unique(array_filter($npkList)));
                if (empty($npkList)) return '';

                $karyawan = $this->dataPatrol->henkaten->table('master_data_karyawan')
                    ->select('npk, nama')
                    ->whereIn('npk', $npkList)
                    ->get()
                    ->getResultArray();

                $namaList = array_column($karyawan, 'nama');
                return implode(',', $namaList);
            };

            // =====================
            // STRING PLAN & STRING ACTUAL
            // =====================
            $npkPlanList   = array_column($planRows, 'npk');
            $npkActualList = array_column($actualRows, 'npk');

            $auditorPlanText   = $getNamaTextByNpkList($npkPlanList);     // "AudPlan1,AudPlan2"
            $auditorActualText = $getNamaTextByNpkList($npkActualList);   // "AudAct1,AudAct2"

            // =====================
            // OUTPUT FIELD
            // =====================
            $nama_auditor_plan   = '';
            $nama_auditor_actual = '';

            if ($type === 'plan') {
                # ambil SEMUA npk plan (karena multiple)
                $npk_plan_list = array_values(array_unique(array_filter(array_column($planRows, 'npk'))));


                $data_auditor = $this->dataPatrol->get_dataAllAuditor();

                foreach ($data_auditor as $dk) {
                    $isSelected = in_array($dk['npk'], $npk_plan_list) ? 'selected' : '';

                    $npkEsc  = htmlspecialchars((string)$dk['npk'], ENT_QUOTES, 'UTF-8');
                    $namaEsc = htmlspecialchars((string)$dk['nama'], ENT_QUOTES, 'UTF-8');

                    $nama_auditor_plan .= "<option value='{$npkEsc}' {$isSelected}>{$namaEsc}</option>";
                }

                // nama_auditor_actual tidak muncul saat type plan
                $nama_auditor_actual = '';
            } elseif ($type === 'actual') {
                // ACTUAL:
                // nama_auditor_plan = STRING auditor PLAN
                // nama_auditor_actual = STRING auditor ACTUAL
                $nama_auditor_plan   = $auditorPlanText;
                $nama_auditor_actual = $auditorActualText;
            }

            // =====================
            // TANGGAL
            // =====================
            // ambil tanggal patrol dari plan (misal ambil signed_at terakhir yang terisi)
            $tanggal_patrol = null;
            if (!empty($planRows)) {
                $signedPlan = array_filter(array_column($planRows, 'signed_at'));
                rsort($signedPlan);
                $tanggal_patrol = $signedPlan[0] ?? null;
            }

            // ambil tanggal actual terakhir
            $tanggal_actual = null;
            if (!empty($actualRows)) {
                $signedAct = array_filter(array_column($actualRows, 'signed_at'));
                rsort($signedAct);
                $tanggal_actual = $signedAct[0] ?? null;
            }

            // =====================
            // RESPONSE
            // =====================
            $kirim = [
                'nama_auditor_plan'   => $nama_auditor_plan,     // plan: HTML <option> | actual: "AudPlan1,AudPlan2"
                'nama_auditor_actual' => $nama_auditor_actual,   // hanya terisi jika type=actual -> "AudAct1,AudAct2"
                'tanggal_patrol'      => $tanggal['tanggal_patrol'],
                'tanggal_actual'      => $tanggal['tanggal_actual'],
            ];

            return $this->response->setJSON(['schedule' => $kirim]);
        } else if ($keterangan == 'edit_schedule') { #function untuk mengedit data schedule patrol

            $id_schedule    = $this->request->getPost('id_schedule');
            $tanggal_patrol = $this->request->getPost('tanggal_patrol');

            // dari AJAX: auditor[0], auditor[1] -> ini NPK
            $auditors = $this->request->getPost('auditor'); #ini NPK
            if (empty($auditors)) {
                // fallback kalau masih pakai id_auditor (anggap juga NPK)
                $single = $this->request->getPost('id_auditor');
                $auditors = !empty($single) ? [$single] : [];
            }

            if (!is_array($auditors)) $auditors = [$auditors];
            $npkNew = array_values(array_unique(array_filter($auditors)));

            $this->dataPatrol->db->transBegin();

            try {
                # 1) CEK: kalau sudah berjalan (actual & keterangan=1) -> tidak boleh edit
                $alreadyRun = $this->dataPatrol->db->table('dt_daftar_hadir')
                    ->where('id_schedule', $id_schedule)
                    ->where('keterangan', 1)
                    ->where('type_data', 'actual')
                    ->countAllResults();

                if ($alreadyRun > 0) {
                    $this->dataPatrol->db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Quality Patrol sudah berjalan, tidak bisa mengubah data'
                    ]);
                }

                # 2) UPDATE dt_schedule (kolom yang ada: tanggal_patrol)
                $this->dataPatrol->db->table('dt_schedule')
                    ->where('id_schedule', $id_schedule)
                    ->update(['tanggal_patrol' => $tanggal_patrol]);

                if (empty($npkNew)) {
                    $this->dataPatrol->db->transRollback();
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Auditor (NPK) kosong'
                    ]);
                }

                # 3) Ambil NPK existing PLAN untuk schedule ini (role=2 & type_data=plan)
                $existingRows = $this->dataPatrol->db->table('dt_daftar_hadir')
                    ->select('npk')
                    ->where('id_schedule', $id_schedule)
                    ->where('role', 2)
                    ->where('type_data', 'plan')
                    ->get()
                    ->getResultArray();

                $npkExisting = array_values(array_unique(array_filter(array_map(function ($r) {
                    return $r['npk'] ?? null;
                }, $existingRows))));

                # 4) Cek apakah ada npk yang sama
                $intersection = array_values(array_intersect($npkNew, $npkExisting));

                // Helper: insert satu-satu (lebih aman untuk SQL Server dibanding insertBatch di beberapa setup)
                $insertOne = function ($npk) use ($id_schedule) {
                    return $this->dataPatrol->db->table('dt_daftar_hadir')->insert([
                        'npk'            => $npk,
                        'role'           => 2,
                        'signature_path' => null,
                        'signed_at'      => null,
                        'id_schedule'    => $id_schedule,
                        'keterangan'     => 0,
                        'type_data'      => 'plan',
                    ]);
                };

                if (!empty($intersection)) {
                    // Ada minimal 1 yang sama -> insert hanya yang beda
                    $toInsert = array_values(array_diff($npkNew, $npkExisting));

                    foreach ($toInsert as $npk) {
                        $insertOne($npk);
                    }
                } else {
                    // Tidak ada yang sama sama sekali -> reset data by schedule, lalu insert baru
                    $this->dataPatrol->db->table('dt_daftar_hadir')
                        ->where('id_schedule', $id_schedule)
                        ->delete();

                    foreach ($npkNew as $npk) {
                        $insertOne($npk);
                    }
                }

                // OPTIONAL: validasi benar-benar masuk
                $countPlanNow = $this->dataPatrol->db->table('dt_daftar_hadir')
                    ->where('id_schedule', $id_schedule)
                    ->where('role', 2)
                    ->where('type_data', 'plan')
                    ->countAllResults();

                $this->dataPatrol->db->transCommit();

                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Data schedule & daftar hadir berhasil diperbarui',
                    'plan_count' => $countPlanNow
                ]);
            } catch (\Throwable $e) {
                $this->dataPatrol->db->transRollback();
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        } else if ($keterangan == 'hapus_user') { # function untuk menghapus data user Administrator
            $id_user = $this->request->getPost('id_user');

            // Hapus data user dari database
            $this->dataPatrol->db->table('users')->where('id', $id_user)->delete();
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data user berhasil dihapus',

            ]);
        } else if ($keterangan == 'hapus_schedule') {
            $id_schedule = $this->request->getPost('id_schedule');

            // Hapus data schedule dari database
            $this->dataPatrol->db->table('dt_schedule')->where('id_schedule', $id_schedule)->delete();
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data schedule berhasil dihapus',
            ]);
        } else if ($keterangan == 'get_detail_dept_qp') {
            $id_dept = $this->request->getPost('id_dept');
            $loop = $this->dataPatrol->getSeksi_byDeptId($id_dept);
            $html = '';
            $i = 1;
            foreach ($loop as $l) {
                $html .= '  <tr>';
                $html .= '  <td>' . $i++ . '</td>';
                $html .= ' <td>' . $l['section'] . '</td>';
                $html .= '<td class="text-center">';
                $html .= ' <button class="btn btn-sm btn-primary btn_section_edit" data-id="' . $l['id_section'] . '">Edit</button>';
                $html .= '  <button class="btn btn-sm btn-danger btn_section_hapus" data-id="' . $l['id_section'] . '">Hapus</button>';
                $html .= ' </td>';
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $html
            ]);
        }
    }
}
