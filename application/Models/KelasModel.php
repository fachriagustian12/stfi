<?php
namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'data_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'no_kelas', 'tanggal', 'status', 'matkul', 'jam_mulai', 'jam_akhir', 'created_by', 'created_at', 'updated_by', 'updated_at'];
}
