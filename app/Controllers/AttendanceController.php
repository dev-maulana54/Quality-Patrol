<?php

namespace App\Controllers;

use App\Models\AttendanceModel;
use App\Models\Model_data_patrol;
use CodeIgniter\HTTP\ResponseInterface;

class AttendanceController extends BaseController
{
    protected $dataPatrol;

    public function __construct()
    {
        $this->dataPatrol = new Model_data_patrol();
    }

    /**
     * Buat nama file unik jika file sudah ada:
     * contoh: gambar.png -> gambar1.png -> gambar2.png
     */
    private function makeUniqueFilename(string $dir, string $filename): string
    {
        $dir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;

        // Sanitasi sederhana (hindari path traversal)
        $filename = trim($filename);
        $filename = str_replace(["..", "/", "\\"], "", $filename);

        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        $candidate = $filename;
        $i = 1;

        while (is_file($dir . $candidate)) {
            $candidate = $name . $i . ($ext ? '.' . $ext : '');
            $i++;

            if ($i > 9999) {
                $candidate = $name . '_' . date('YmdHis') . ($ext ? '.' . $ext : '');
                break;
            }
        }

        return $candidate;
    }

    public function signDigital(): ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid request']);
        }

        // Cek Content-Type (JSON atau FormData)
        $contentType = (string) $this->request->getHeaderLine('Content-Type');
        $isJson = str_contains($contentType, 'application/json');

        $json = null;
        if ($isJson) {
            try {
                $json = $this->request->getJSON(true);
            } catch (\Throwable $e) {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => 'Body JSON tidak valid.'
                ]);
            }
        }

        // Helper ambil field dari POST(FormData) atau JSON
        $get = function (string $key) use ($json) {
            return $this->request->getPost($key) ?? ($json[$key] ?? null);
        };

        $signType    = $get('sign_type');     // digital / upload
        $id_schedule = $get('id_schedule');
        $valueSign   = $get('value_sign');    // tambah_d_hadir / sign_user
        $id_sign     = $get('id_sign');       // wajib untuk sign_user (update plan)

        $npk  = $get('npk');  // wajib untuk tambah_d_hadir
        $role = $get('role'); // wajib untuk tambah_d_hadir

        if (!$signType || !$id_schedule || !$valueSign) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Data tidak lengkap (sign_type / id_schedule / value_sign).'
            ]);
        }

        $model = new AttendanceModel();

        $isTambah = ($valueSign === 'tambah_d_hadir');
        $isUpdate = ($valueSign === 'sign_user');

        if (!$isTambah && !$isUpdate) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'value_sign tidak dikenali.'
            ]);
        }

        $existingPlan = null;

        // =========================
        // VALIDASI MODE
        // =========================
        if ($isTambah) {
            if (!$npk || !$role) {
                return $this->response->setStatusCode(422)->setJSON([
                    'message' => 'Data tidak lengkap (npk / role).'
                ]);
            }

            // ✅ 1 user hanya 1x di list (plan) untuk schedule tsb
            if (
                $model->where('npk', $npk)
                ->where('id_schedule', $id_schedule)
                ->where('type_data', 'plan')
                ->countAllResults() > 0
            ) {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'User ini sudah ada di daftar hadir (plan) untuk schedule ini.'
                ]);
            }
        }

        if ($isUpdate) {
            if (!$id_sign) {
                return $this->response->setStatusCode(422)->setJSON([
                    'message' => 'id_sign wajib untuk update (sign_user).'
                ]);
            }

            $existingPlan = $model->find($id_sign);
            if (!$existingPlan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'message' => 'Data auditor (plan) tidak ditemukan untuk id_sign tersebut.'
                ]);
            }

            // Pastikan id_sign sesuai schedule
            if ((string)($existingPlan['id_schedule'] ?? '') !== (string)$id_schedule) {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'id_sign tidak sesuai dengan id_schedule.'
                ]);
            }

            // ✅ Pastikan yang diupdate adalah PLAN
            if (($existingPlan['type_data'] ?? '') !== 'plan') {
                return $this->response->setStatusCode(409)->setJSON([
                    'message' => 'Data yang di-sign harus berasal dari list (type_data = plan).'
                ]);
            }

            // Untuk update: npk/role ambil dari existing plan
            $npk  = $npk  ?: ($existingPlan['npk'] ?? null);
            $role = $role ?: ($existingPlan['role'] ?? null);

            if (!$npk || !$role) {
                return $this->response->setStatusCode(422)->setJSON([
                    'message' => 'Data existing tidak lengkap (npk/role).'
                ]);
            }
        }

        // Folder simpan (public)
        $dir = FCPATH . 'uploads/signatures/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = null;

        // =========================
        // DIGITAL (base64)
        // =========================
        if ($signType === 'digital') {
            $signatureData = $json['signature_data'] ?? $this->request->getPost('signature_data');

            if (!$signatureData) {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'Tanda tangan digital tidak ditemukan.']);
            }

            if (!preg_match('/^data:image\/png;base64,/', $signatureData)) {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'Format tanda tangan digital tidak valid.']);
            }

            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);

            $binary = base64_decode($signatureData, true);
            if ($binary === false) {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'Gagal decode tanda tangan.']);
            }

            if (strlen($binary) > 2 * 1024 * 1024) {
                return $this->response->setStatusCode(413)->setJSON(['message' => 'Ukuran tanda tangan terlalu besar.']);
            }

            $baseName = 'sign_digital_' . $npk . '_' . date('YmdHis') . '.png';
            $filename = $this->makeUniqueFilename($dir, $baseName);

            if (file_put_contents($dir . $filename, $binary) === false) {
                return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal menyimpan file tanda tangan digital.']);
            }
        }

        // =========================
        // UPLOAD (FormData file)
        // =========================
        elseif ($signType === 'upload') {
            $file = $this->request->getFile('sign_file');

            if (!$file || !$file->isValid()) {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'File tanda tangan tidak valid.']);
            }

            $mime = $file->getMimeType();
            if (!in_array($mime, ['image/png', 'image/jpeg'])) {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'File harus PNG atau JPG.']);
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                return $this->response->setStatusCode(413)->setJSON(['message' => 'Ukuran file maksimal 2MB.']);
            }

            $originalName = $file->getName();
            $filename = $this->makeUniqueFilename($dir, $originalName);

            if (!$file->move($dir, $filename)) {
                return $this->response->setStatusCode(500)->setJSON(['message' => 'Gagal upload file tanda tangan.']);
            }
        } else {
            return $this->response->setStatusCode(422)->setJSON(['message' => 'Jenis tanda tangan tidak dikenali.']);
        }

        $relativePath = 'uploads/signatures/' . $filename;
        $signedAt = date('Y-m-d H:i:s');

        // =========================
        // INSERT PLAN (tambah_d_hadir)
        // =========================
        if ($isTambah) {
            $newId = $model->insert([
                'npk'            => $npk,
                'role'           => $role,
                'id_schedule'    => $id_schedule,
                'signature_path' => $relativePath,
                'signed_at'      => $signedAt,
                'keterangan'     => 1,
                'type_data'      => 'actual',
            ], true);

            if (!$newId) {
                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Gagal simpan ke database.',
                    'errors'  => $model->errors()
                ]);
            }

            return $this->response->setJSON([
                'message' => 'Daftar Hadir berhasil',
                'mode' => 'insert',
                'id' => $newId
            ]);
        }

        // =========================
        // sign_user: UPDATE PLAN + INSERT ACTUAL (selalu)
        // =========================
        $plan = $existingPlan ?: $model->find($id_sign);
        if (!$plan) {
            @unlink($dir . $filename);
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Data auditor (plan) tidak ditemukan.'
            ]);
        }

        $oldPlanPath = $plan['signature_path'] ?? null;

        $db = $model->db;
        $db->transStart();

        // 1) Update PLAN (type_data tetap plan)
        $okPlan = $model->update($id_sign, [
            'signature_path' => $relativePath,
            'signed_at'      => $signedAt,
            'keterangan'     => 1,
        ]);

        if (!$okPlan) {
            $db->transRollback();
            @unlink($dir . $filename);
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Gagal update data plan.',
                'errors'  => $model->errors()
            ]);
        }

        // 2) SELALU INSERT ACTUAL BARU
        $actualId = $model->insert([
            'npk'            => $npk,
            'role'           => $role,
            'id_schedule'    => $id_schedule,
            'signature_path' => $relativePath,
            'signed_at'      => $signedAt,
            'keterangan'     => 1,
            'type_data'      => 'actual',
        ], true);

        if (!$actualId) {
            $db->transRollback();
            @unlink($dir . $filename);
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Gagal insert data actual.',
                'errors'  => $model->errors()
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            @unlink($dir . $filename);
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Transaksi database gagal.'
            ]);
        }

        // Update dt_schedule hanya tanggal_actual
        $tanggal = date('d/m/Y');
        $this->dataPatrol->db->table('dt_schedule')
            ->where('id_schedule', $id_schedule)
            ->update([
                'tanggal_actual' => $tanggal,
            ]);

        // Hapus file lama PLAN setelah update sukses (opsional)
        if ($oldPlanPath) {
            $oldAbs = FCPATH . ltrim($oldPlanPath, '/');
            if (is_file($oldAbs)) {
                @unlink($oldAbs);
            }
        }

        return $this->response->setJSON([
            'message' => 'Daftar Hadir berhasil',
            'mode' => 'update_plan_insert_actual',
            'id_plan' => $id_sign,
            'id_actual' => $actualId,
            'signature_path' => $relativePath
        ]);
    }
}
