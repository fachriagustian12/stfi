<?php 
namespace App\Models;

use CodeIgniter\Model;

class RisetDosenModel extends Model{
    protected $table = 'data_riset_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_dosen', 
        'nm_dosen', 
        'nidn', 
        'kd_periode_lkd',
        'tahun_ajaran',
        'jenis_karyailmiah',
        'judul',
        'issn',
        'volume',
        'nomor',
        'tahun',
        'created_at'
    ];

}
