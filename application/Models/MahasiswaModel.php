<?php 
namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model{
    protected $table = 'data_mahasiswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'npm', 'semester', 'prodi', 'status_mahasiswa', 'status_perwalian', 'create_date', 'update_date', 'create_by', 'update_by'];

}
