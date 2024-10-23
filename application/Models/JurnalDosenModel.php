<?php 
namespace App\Models;

use CodeIgniter\Model;

class JurnalDosenModel extends Model{
    protected $table = 'data_jurnal_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode_dosen', 
        'nm_dosen', 
        'nidn', 
        'kd_periode_lkd',
        'tahun_ajaran',
        'jenis_jurnal',
        'judul',
        'issn',
        'volume',
        'nomor',
        'tahun',
        'created_at',
        'path',
        'url_file'
    ];

    public function deleteData($id = null, $table = null)
    {
        $res = $this->db->table("data_$table")->where('id', $id)->delete();
        return  $res;
    }
}
