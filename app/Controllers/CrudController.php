<?php

namespace App\Controllers;

use App\Models\Model_data_patrol;
use CodeIgniter\HTTP\ResponseInterface;

class CrudController extends BaseController
{
    protected $dataPatrol;
    public function __construct()
    {

        $this->dataPatrol = new Model_data_patrol();
    }
    public function authLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        if (! $username || ! $password) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'msg' => 'Username dan password wajib diisi',]);
        }
        // 1) login ke API external 
        $api = $this->request_login_api($username, $password);
        if (! $api['ok']) {
            return $this->response->setStatusCode(401)->setJSON($api);
        }
        // 2) ambil NPK dari API 
        $npk = $api['npk'] ?? null;
        if (! $npk) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'msg' => 'Login API sukses tapi NPK tidak ditemukan di response', 'api' => $api['api_user'] ?? null,]);
        }
        // 3) cari user lokal berdasarkan NPK 
        $userLocal = $this->dataPatrol->db->table('users')->where('npk', $npk)->get()->getRowArray();
        if (! $userLocal) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'msg' => 'User belum di tambahkan ke dalam Portal Quality Patrol!', 'npk' => $npk,]);
        }
        // 4) set session berdasarkan user lokal 
        session()->set([
            'isLoggedIn' => true,
            'user_id' => $userLocal['id'] ?? null,
            'npk' => $userLocal['npk'] ?? $npk,
            'role' => $userLocal['role'] ?? 'user',
        ]);
        return $this->response->setJSON([
            'ok' => true,
            'msg' => 'Login berhasil',
            'user' => $userLocal,
            'npk' => $npk,
        ]);
    }

    //     public function authLogin()
    // {
    //     $username = $this->request->getPost('username');
    //     $password = $this->request->getPost('password');

    //     if (! $username || ! $password) {
    //         return $this->response->setStatusCode(422)->setJSON([
    //             'ok'  => false,
    //             'msg' => 'Username dan password wajib diisi',
    //         ]);
    //     }

    //     // 1) login ke API external
    //     $api = $this->request_login_api($username, $password);
    //     if (! $api['ok']) {
    //         return $this->response->setStatusCode(401)->setJSON($api);
    //     }

    //     // 2) ambil NPK dari API
    //     $npk = $api['npk'] ?? null;
    //     if (! $npk) {
    //         return $this->response->setStatusCode(500)->setJSON([
    //             'ok'  => false,
    //             'msg' => 'Login API sukses tapi NPK tidak ditemukan di response',
    //             'api' => $api['api_user'] ?? null,
    //         ]);
    //     }

    //     // 3) ambil semua baris user berdasarkan NPK (karena 1 NPK bisa punya banyak role)
    //     $rows = $this->dataPatrol->db->table('users')
    //         ->select('id, npk, role')
    //         ->where('npk', $npk)
    //         ->get()
    //         ->getResultArray();

    //     if (! $rows || count($rows) === 0) {
    //         return $this->response->setStatusCode(404)->setJSON([
    //             'ok'  => false,
    //             'msg' => 'User belum di tambahkan ke dalam Portal Quality Patrol!',
    //             'npk' => $npk,
    //         ]);
    //     }

    //     // ambil daftar role unik
    //     $roleLabel = [1 => 'Administrator', 2 => 'Auditor', 3 => 'Auditee'];

    //     $uniqueRoles = [];
    //     $roleToUserId = []; // mapping role -> user row id (kalau ada duplikat, ambil yg pertama)
    //     foreach ($rows as $r) {
    //         $rid = (int) $r['role'];
    //         if (!isset($uniqueRoles[$rid])) {
    //             $uniqueRoles[$rid] = [
    //                 'id'   => $rid,
    //                 'name' => $roleLabel[$rid] ?? ('Role ' . $rid),
    //             ];
    //         }
    //         if (!isset($roleToUserId[$rid])) {
    //             $roleToUserId[$rid] = (int) $r['id'];
    //         }
    //     }

    //     $roles = array_values($uniqueRoles);

    //     // 4) kalau cuma 1 role -> set session final langsung
    //     if (count($roles) === 1) {
    //         $onlyRoleId = (int) $roles[0]['id'];
    //         $userRowId  = $roleToUserId[$onlyRoleId] ?? ($rows[0]['id'] ?? null);

    //         session()->set([
    //             'isLoggedIn'  => true,
    //             'npk'         => $npk,
    //             'user_row_id' => $userRowId,      // id baris users untuk role aktif
    //             'active_role' => $onlyRoleId,
    //             'role_name'   => $roles[0]['name'],
    //         ]);

    //         session()->remove('pending_login');

    //         return $this->response->setJSON([
    //             'ok' => true,
    //             'msg' => 'Login berhasil',
    //             'need_role_select' => false,
    //             'redirect' => base_url('summary'),
    //         ]);
    //     }

    //     // 5) kalau lebih dari 1 role -> simpan pending login, kirim roles ke frontend
    //     session()->set([
    //         'pending_login' => [
    //             'npk' => $npk,
    //         ],
    //     ]);

    //     return $this->response->setJSON([
    //         'ok' => true,
    //         'msg' => 'Login berhasil, silakan pilih role',
    //         'need_role_select' => true,
    //         'roles' => $roles,
    //     ]);
    // }

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

        // validasi bahwa NPK ini memang punya role tsb (dari tabel users)
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

        // set session final
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

            // sementara (karena issuer cert)
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
            // Pastikan flag sukses dari API
            if (!empty($decoded['is_login']) && !empty($decoded['npk'])) {
                return [
                    'ok'       => true,
                    'msg'      => 'Login API berhasil',
                    'npk'      => $decoded['npk'],
                    'api_user' => $decoded, // simpan full data kalau perlu
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

        // Ambil data dari POST
        $namaDept = $this->request->getPost('nama_dept');
        $keterangan = $this->request->getPost('keterangan');

        if ($keterangan == 'tambah_dept') {
            $insert = $this->dataPatrol->insert_dept([
                'nama_dept' => $namaDept,
            ]);

            if ($insert) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan data']);
            }
        } else if ($keterangan == 'get_dept') {

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
        } else if ($keterangan == 'tambah_seksi') {
            // Proses tambah seksi di sini
            $idDept = $this->request->getPost('id_dept');
            $namaSeksi = $this->request->getPost('nama_seksi');

            $data = [
                'dt_id_dept' => $idDept,
                'nama_seksi' => $namaSeksi,
            ];

            // Simpan data seksi ke database (implementasi sesuai kebutuhan)
            $this->dataPatrol->insert_seksi($data);
            $loop_seksi = $this->dataPatrol->getSeksi_byDeptId($idDept);
            $html = '';
            foreach ($loop_seksi as $index => $seksi) {
                $html .= '<tr>
                    <th scope="row">' . ($index + 1) . '</th>
                    <td>' . $seksi['nama_seksi'] . '</td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapus_seksi" data-id="' . $seksi['id_seksi'] . '" data-iddept="' . $idDept . '">Hapus</button></td>
                </tr>';
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Seksi berhasil ditambahkan', 'html' => $html]);
        } else if ($keterangan == 'hapus_seksi') {
            // Proses hapus seksi di sini
            $idSeksi = $this->request->getPost('id_seksi');
            $idDept = $this->request->getPost('id_dept');
            // Hapus data seksi dari database (implementasi sesuai kebutuhan)
            $this->dataPatrol->db->table('dt_seksi')->where('id_seksi', $idSeksi)->delete();
            $loop_seksi = $this->dataPatrol->getSeksi_byDeptId($idDept);
            $html = '';
            foreach ($loop_seksi as $index => $seksi) {
                $html .= '<tr>
                    <th scope="row">' . ($index + 1) . '</th>
                    <td>' . $seksi['nama_seksi'] . '</td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm hapus_seksi" data-id="' . $seksi['id_seksi'] . '" data-iddept="' . $idDept . '">Hapus</button></td>
                </tr>';
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Seksi berhasil dihapus', 'html' => $html]);
        } else if ($keterangan == 'update_dept') {
            $idDept = $this->request->getPost('id_dept');
            $new_dept = $this->request->getPost('nama_dept');
            $data =  [
                'id_dept' => $idDept,
                'nama_dept' => $new_dept,
            ];
            $this->dataPatrol->update_dept($data);
        } else if ($keterangan == 'hapus_dept') {
            $idDept = $this->request->getPost('id_dept');
            // Hapus data dept dari database (implementasi sesuai kebutuhan)
            $this->dataPatrol->db->table('dt_dept')->where('id_dept', $idDept)->delete();
            // to do hapus juga seksi terkait
            $this->dataPatrol->db->table('dt_seksi')->where('dt_id_dept', $idDept)->delete();
        } else if ($keterangan == 'get_dept_seksi') { // Ambil Departemen dan Seksi berdasarkan NPK
            $npk_user = $this->request->getPost('npk');
            $loop_seksi = $this->dataPatrol->getDeptSectionbyId($npk_user);
            $nama_dept_user = $loop_seksi['departement'];
            $nama_seksi_user = $loop_seksi['section'];

            return $this->response->setJSON(['nama_dept_user' => $nama_dept_user, 'nama_seksi_user' => $nama_seksi_user]);
        } else if ($keterangan == 'get_seksi_by_dept') { // Ambil Seksi berdasarkan Departemen
            $deptId = $this->request->getPost('id_dept');
            $loop_seksi = $this->dataPatrol->getSeksi_byDeptId($deptId);

            $options = '';
            foreach ($loop_seksi as $seksi) {
                $options .= '<option value="' . $seksi['id_section'] . '" data-section="' . $seksi['section'] . '">' . $seksi['section'] . '</option>';
            }

            return $this->response->setJSON(['options' => $options]);
        } else if ($keterangan == 'tambah_user') { // Tambah User Portal
            $npk = $this->request->getPost('npk_user');


            $role = $this->request->getPost('role_user');
            #todo : cek apakah npk sudah ada di tabel users dan username sudah ada



            $data = [
                'npk' => $npk,
                'created_at' => date('Y-m-d H:i:s'),
                'role' => $role,
            ];

            // Simpan data user ke database (implementasi sesuai kebutuhan)
            $this->dataPatrol->db->table('users')->insert($data);

            return $this->response->setJSON(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
        } else if ($keterangan == 'find_auditee_by_seksi') {
            #todo : ambil nama seksi yang jabatannya Kepala Seksi berdasarkan id_seksi di master_data_karyawan dan ambil
            $id_seksi = $this->request->getPost('id_seksi');
            $auditee = $this->dataPatrol->db->table('master_data_karyawan')
                ->where('id_section', $id_seksi)
                ->where('jabatan', 'Kepala Seksi')
                ->get()
                ->getRowArray();

            // Cek apakah data ada atau null
            if ($auditee && isset($auditee['nama'])) {
                $nama_auditee = $auditee['nama'];
            } else {
                $nama_auditee = 'Kepala seksi tidak ada';
            }
            return $this->response->setJSON(['auditee' => $nama_auditee]);
        } else if ($keterangan == 'tambah_temuan_patrol') {
            $getdata_user = $this->dataPatrol->getdata_karyawan_byUsername(session()->get('npk'));

            $rekapJson  = $this->request->getPost('rekap_temuan');
            $rekapList  = json_decode($rekapJson, true) ?? [];

            // ambil file yang diupload: evidence_files[]
            $allFiles = $this->request->getFiles();
            $uploadedFiles = $allFiles['evidence_files'] ?? []; // array UploadedFile

            // folder tujuan (public/uploads/findings_evidence/)
            $targetDir = FCPATH . 'uploads/findings_evidence/';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $batchData = [];

            foreach ($rekapList as $item) {

                $savedFileName = null;

                // kalau item punya file_index, ambil file yg sesuai index
                $fileIndex = $item['file_index'] ?? null;

                if ($fileIndex !== null && isset($uploadedFiles[$fileIndex])) {
                    $file = $uploadedFiles[$fileIndex];

                    if ($file && $file->isValid() && !$file->hasMoved()) {

                        // bikin nama unik
                        $ext = $file->getClientExtension();
                        $newName = 'evidence_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5));
                        if ($ext) $newName .= '.' . $ext;

                        // move file ke folder
                        $file->move($targetDir, $newName);

                        $savedFileName = $newName; // simpan filename ke DB
                    }
                }

                $batchData[] = [
                    'tanggal_patrol'              => $this->request->getPost('tanggal_patrol'),
                    'id_auditor'                  => session()->get('user_id'),
                    'nama_auditor'                => $getdata_user['nama'],
                    'nama_auditee'                => $this->request->getPost('nama_auditee'),
                    'id_departement'              => $this->request->getPost('deptId'),
                    'id_section'                  => $this->request->getPost('seksiId'),
                    'deskripsi_temuan'            => $item['deskripsi_temuan'] ?? null,
                    'pic_action_departement_id'   => $item['pic_action_dept_id'] ?? null,
                    'pic_action_section_id'       => $item['pic_action_section_id'] ?? null,
                    'due_date'                    => 0,
                    'status'                      => 3,
                    'id_dt_schedule' => $this->request->getPost('id_schedule'),
                    // ✅ butuh kolom di DB, misalnya evidence_file
                    'evidence_file'               => $savedFileName,
                ];
            }

            $this->dataPatrol->db->table('dt_temuan_patrol')->insertBatch($batchData);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Temuan patrol berhasil ditambahkan'
            ]);
        } else if ($keterangan == 'get_temuan_by_id') {
            $id_temuan = $this->request->getPost('id_temuan');
            #todo : ambil data temuan patrol berdasarkan id_temuan_patrol dan join tabel departement dan section
            $temuan = $this->dataPatrol->db->table('dt_temuan_patrol')
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
                ->where('dt_temuan_patrol.id_temuan_patrol', $id_temuan)
                ->get()
                ->getRowArray();


            #PIC Action 
            $pic_action = $this->dataPatrol->get_Alldata_seksi();
            $nama_pic = '';

            foreach ($pic_action as $pa) {
                $isSelected5 = $temuan['pic_action_section_id'] == $pa['id_section'] ? 'selected' : '';
                $nama_pic .= "<option value='{$pa['id_section']}' {$isSelected5}>{$pa['section']}</option>";
            }
            # Data User Login dengan Role Auditor
            $data_auditor = $this->dataPatrol->get_dataAllAuditor();
            $nama_auditor = '';

            foreach ($data_auditor as $dk) {
                $isSelected = $temuan['id_auditor'] == $dk['user_id'] ? 'selected' : '';
                $nama_auditor .= "<option value='{$dk['user_id']}' {$isSelected}>{$dk['nama']}</option>";
            }
            # Data User Login dengan Role Auditee
            $data_auditee = $this->dataPatrol->get_dataAllAuditee();
            $nama_auditee = '';

            foreach ($data_auditee as $da) {
                $isSelected2 = $temuan['nama_auditee'] == $da['nama'] ? 'selected' : '';
                $nama_auditee .= "<option value='{$da['user_id']}' {$isSelected2}>{$da['nama']}</option>";
            }

            #data Nama Section
            $list_section = $this->dataPatrol->get_Alldata_seksi();
            $section = '';
            foreach ($list_section as $ls) {
                $isSelected3 = $temuan['id_section'] == $ls['id_section'] ? 'selected' : '';
                $section .=  "<option value='{$ls['id_section']}' {$isSelected3}>{$ls['section']}</option>";
            }


            if (session()->get('role') == 1) {

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
                    'finding_evidence' => $temuan['evidence_file']
                ];
                return $this->response->setJSON(['temuan' => $kirim]);
            } else if (session()->get('role') == 3) {
                $kirim = [
                    'id_auditor' => $temuan['id_auditor'],
                    'id_temuan_patrol' => $temuan['id_temuan_patrol'],
                    'tanggal_patrol' => $temuan['tanggal_patrol'],
                    'nama_auditor' => $temuan['nama_auditor'],
                    'nama_auditee' => $temuan['nama_auditee'],
                    'section_name' => $temuan['section_name'],
                    'deskripsi_temuan' => $temuan['deskripsi_temuan'],
                    'analisa_penyebab' => $temuan['analisa_penyebab'],
                    'action' => $temuan['action'],
                    'pic_section_name' => $nama_pic,
                    'nama_file' => $temuan['nama_file'],
                    'finding_evidence' => $temuan['evidence_file']
                ];
                return $this->response->setJSON(['temuan' => $kirim]);
            } else {
                return $this->response->setJSON(['temuan' => $temuan]);
            }
        } else if ($keterangan == 'update_temuan_patrol') {
            $id_temuan = $this->request->getPost('id_temuan');
            $file      = $this->request->getFile('file');

            $data_update = []; // mulai dari array kosong

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

            // ==== STATUS ====
            if (session()->get('role') == 2 || session()->get('role') == 1) {
                $status = $this->request->getPost('status');
                if ($status !== null && $status !== '') {   // biar '0' juga bisa
                    $data_update['status'] = $status;
                }
            } else {
                $get_section_dept = $this->dataPatrol->getSection_andDeptByID($this->request->getPost('pic_action'));
                $data_update['pic_action_section_id'] = $this->request->getPost('pic_action');
                $data_update['pic_action_departement_id'] = $get_section_dept['id_departement'];

                $data_update['status'] = 2; // langsung simpan
            }

            // ==== FIELD LAIN ====
            $tanggal_patrol = $this->request->getPost('tanggal_patrol');
            $due_date       = $this->request->getPost('due_date');

            $data_update['deskripsi_temuan'] = $this->request->getPost('deskripsi_temuan');
            $data_update['analisa_penyebab'] = $this->request->getPost('analisa_penyebab');
            $data_update['action']           = $this->request->getPost('action');

            if (!empty($tanggal_patrol)) {
                $data_update['tanggal_patrol'] = $tanggal_patrol;
            }

            if (!empty($due_date)) {
                $data_update['due_date'] = $due_date;
            }


            $this->dataPatrol->db->table('dt_temuan_patrol')
                ->where('id_temuan_patrol', $id_temuan)
                ->update($data_update);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data temuan patrol berhasil diperbarui'
            ]);
        } else if ($keterangan == 'hapus_temuan_patrol') {
            $id_temuan_patrol = $this->request->getPost('id_temuan_patrol');
            // Hapus data temuan patrol dari database
            $this->dataPatrol->db->table('dt_temuan_patrol')->where('id_temuan_patrol', $id_temuan_patrol)->delete();
        } else if ($keterangan == 'getData_filter_year') {
            $tahun = $this->request->getPost('tahun');

            // DATA PER BULAN TAHUN SEKARANG
            $dataRaw = $this->dataPatrol->Filter_chartByYearNow($tahun);
            // Siapkan array default 12 bulan
            $open = array_fill(0, 12, 0);
            $inprogress = array_fill(0, 12, 0);
            $close = array_fill(0, 12, 0);
            $total = array_fill(0, 12, 0);

            foreach ($dataRaw as $row) {
                $bulan = (int)$row['bulan'] - 1; // index 0-11

                if ($row['status'] == '3') {
                    $open[$bulan] = (int)$row['total'];
                } elseif ($row['status'] == '2') {
                    $inprogress[$bulan] = (int)$row['total'];
                } elseif ($row['status'] == '1') {
                    $close[$bulan] = (int)$row['total'];
                }

                $total[$bulan] += (int)$row['total'];
            }
            // Contoh struktur:
            $response = [

                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'open' => $open,         // array numeric (12 nilai)
                'progress' => $inprogress, // array numeric (12 nilai)
                'close' => $close,       // array numeric (12 nilai)
                'total' => $total        // array numeric (12 nilai) -> spline
            ];

            return $this->response->setJSON($response);
        } else if ($keterangan == 'getData_filter_rangeDate') {
            $startDate = $this->request->getPost('startDate'); // format: YYYY-MM-DD
            $endDate   = $this->request->getPost('endDate');


            // ambil data dari model
            $rows = $this->dataPatrol->filterRangeDate($startDate, $endDate);

            // === contoh mengubah rows jadi array untuk Highcharts ===
            // sesuaikan dengan struktur datamu ya

            $categories = [];   // misal bulan / area
            $open       = [];
            $progress   = [];
            $close      = [];
            $total      = [];
            $all = [];

            foreach ($rows as $row) {
                // contoh: pakai nama area sebagai kategori
                $categories[] = $row['nama_section'];
                $all[] = $row;
                $open[]     = (int) ($row['open_count'] ?? 0);
                $progress[] = (int) ($row['progress_count'] ?? 0);
                $close[]    = (int) ($row['close_count'] ?? 0);
                $total[]    = (int) ($row['total'] ?? 0);
            }
            // echo "<pre>";
            // var_dump($all);
            return $this->response->setJSON([
                'categories' => $categories,
                'open_count'       => $open,
                'progress_count'   => $progress,
                'close_count'      => $close,
                'total'      => $total,
            ]);
        } else if ($keterangan  == 'getData_filter_dept') {
            $dept      = $this->request->getPost('dept');
            $rows = $this->dataPatrol->filterDept($dept);
            // Siapkan array default 12 bulan
            $open = array_fill(0, 12, 0);
            $inprogress = array_fill(0, 12, 0);
            $close = array_fill(0, 12, 0);
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
                }

                $total[$bulan] += (int)$row['total'];
            }

            $count_temuan = $this->dataPatrol->filterDept2($dept);

            // echo "<pre>";
            // var_dump($open);
            // var_dump($close);
            // var_dump($progress);
            // var_dump($all);
            return $this->response->setJSON([
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'open'       => $open,
                'progress'   => $inprogress,
                'close'      => $close,
                'open_count' => $count_temuan['open_total'],
                'progress_count' => $count_temuan['progress_total'],
                'close_count' => $count_temuan['close_total'],
            ]);
        } else if ($keterangan == 'tambah_schedule_patrol') {
            $tanggal_patrol = $this->request->getPost('tanggal_patrol');
            $dept_id = $this->request->getPost('deptId');
            $section_id = $this->request->getPost('seksiId');
            $id_auditor = $this->request->getPost('auditor');

            $data = [
                'id_auditor' => $id_auditor,
                'id_dept' => $dept_id,
                'id_section' => $section_id,
                'tanggal_patrol' => $tanggal_patrol,
                'created_at' => date('Y-M-D h:m:s')
            ];
            // Simpan data user ke database (implementasi sesuai kebutuhan)
            $this->dataPatrol->db->table('dt_schedule')->insert($data);

            $getnpk = $this->dataPatrol->db->table('users')->select(' npk')->where('id', $id_auditor)->get()->getRowArray();
            $getID_schedule = $this->dataPatrol->db->table('dt_schedule')->where($data)->get()->getRowArray();
            $data2 = [
                'npk' => $getnpk['npk'],
                'role' => 2,
                'id_schedule' => $getID_schedule['id_schedule'],
                'keterangan' => 0
            ];

            $this->dataPatrol->db->table('dt_daftar_hadir')->insert($data2);
            return $this->response->setJSON(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
        } else if ($keterangan == 'ambil_data_schedule') {
            $bulan = $this->request->getPost('month');
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
            // echo "<pre>";
            // var_dump($tanggal_plan_data);
            return $this->response->setJSON([
                'schedule_data' => $tanggal_plan_data,
            ]);
        } else if ($keterangan == 'get_deptSection_bySchedule') {
            $scheduleid = $this->request->getPost('scheduleid');
            $get_data = $this->dataPatrol->get_deptSection_bySchedule($scheduleid);


            $data = [
                'id_departement' => $get_data['id_dept'],
                'departement' => $get_data['departement'],
                'id_section' => $get_data['id_section'],
                'section'     => $get_data['section'],
                'nama'        => $get_data['nama'] ?? 'Kepala Seksi tidak ada',
            ];


            return $this->response->setJSON($data);
        } else if ($keterangan == 'get_data_schedule') {
            $id_schedule = $this->request->getPost('id_schedule');
            $temuan = $this->dataPatrol->db->table('dt_schedule')
                ->where('id_schedule', $id_schedule)
                ->get()
                ->getRowArray();
            $data_auditor = $this->dataPatrol->get_dataAllAuditor();
            $nama_auditor = '';

            foreach ($data_auditor as $dk) {
                $isSelected = $temuan['id_auditor'] == $dk['user_id'] ? 'selected' : '';
                $nama_auditor .= "<option value='{$dk['user_id']}' {$isSelected}>{$dk['nama']}</option>";
            }

            $kirim = [

                'nama_auditor' => $nama_auditor,
                'tanggal_patrol' => $temuan['tanggal_patrol']

            ];
            return $this->response->setJSON(['schedule' => $kirim]);
        } else if ($keterangan == 'edit_schedule') {
            $id_auditor = $this->request->getPost('id_auditor');
            $tanggal_patrol = $this->request->getPost('tanggal_patrol');
            $id_schedule = $this->request->getPost('id_schedule');
            $data = [
                'id_auditor' => $id_auditor,
                'tanggal_patrol' => $tanggal_patrol
            ];
            $this->dataPatrol->db->table('dt_schedule')
                ->where('id_schedule', $id_schedule)
                ->update($data);

            $getNPK = $this->dataPatrol->db->table('users')->where('id', $id_auditor)->get()->getRowArray();

            $data2 = [
                'npk' => $getNPK['npk'],

            ];
            $this->dataPatrol->db->table('dt_daftar_hadir')->where('id_schedule', $id_schedule)->update($data2);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data temuan patrol berhasil diperbarui'
            ]);
        }
    }
}
