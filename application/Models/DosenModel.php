<?php 
namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model{
    protected $table = 'data_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'mata_kuliah', 'jadwal', 'kelas', 'perkuliahan', 'status', 'tugas', 'create_by', 'create_date', 'update_by', 'update_date'];

}
