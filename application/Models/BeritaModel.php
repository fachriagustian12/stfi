<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaModel extends Model
{
    protected $table = 'data_berita';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'redaksi', 'tanggal', 'status', 'path', 'create_by', 'create_date', 'update_by', 'update_date', 'update_date'];

    public function getPagination(?int $perPage = null): array
    {
        $this->builder()
            ->select('data_berita.*, m_user.name')
            ->join('m_user', 'data_berita.create_by = m_user.id');

        return [
            'berita'  => $this->paginate($perPage),
            'pager' => $this->pager,
        ];
    }
}
