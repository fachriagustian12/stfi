<?php 
namespace App\Models;

use CodeIgniter\Model;

class KegiatanModel extends Model{
    protected $table = 'data_kegiatan';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'kegiatan', 'tanggal_kegiatan', 'keterangan', 'create_date', 'create_by', 'update_date', 'update_by', 'status', 'path' ];

}
