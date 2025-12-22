<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'dt_daftar_hadir';
    protected $primaryKey       = 'id_sign';
    protected $allowedFields    = [
        'npk',
        'role',
        'signature_path',
        'signed_at',
        'id_schedule',
        'keterangan'
    ];
    protected $useTimestamps    = false;
}
