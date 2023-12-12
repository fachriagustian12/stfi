<?php

namespace App\Models;

use CodeIgniter\Model;

class YourModel extends Model
{
    protected $table = 'data_berita'; // Ganti dengan nama tabel Anda

    protected $primaryKey = 'id'; // Ganti dengan nama primary key tabel Anda

    protected $allowedFields = ['field1', 'field2', 'field3']; // Sesuaikan dengan kolom yang diizinkan

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $deletedField  = 'deleted_at';

    protected $returnType    = 'array';

    protected $useSoftDeletes = true;

    public function getPaginatedData($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;

        return $this->paginate($perPage, 'default', $offset);
    }
}
