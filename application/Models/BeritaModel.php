<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'data_berita';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'redaksi', 'tanggal', 'status', 'path', 'create_by', 'create_date', 'update_by', 'update_date', 'update_date'];
}
