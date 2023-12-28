<?php
namespace App\Models;

use CodeIgniter\Model;

class PraktikumModel extends Model
{
    protected $table = 'data_jadwal_praktikum';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ruangan_praktikum', 'mata_kuliah_praktikum', 'nama_dosen', 'status', 'tanggal', 'jam_mulai', 'jam_akhir', 'created_by', 'created_at', 'updated_by', 'updated_at'];
}
