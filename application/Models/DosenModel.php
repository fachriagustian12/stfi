<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'data_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'nama', 
        'mata_kuliah', 
        'jadwal', 
        'kelas', 
        'perkuliahan', 
        'status', 
        'tugas', 
        'create_by', 
        'create_date', 
        'update_by', 
        'update_date',
        'kd_dosen',
        'nm_dosen',
        'gelar_depan',
        'gelar_belakang',
        'nidn',
        'a_dosen_homebase',
        'id_kat_dosen',
        'kategori_dosen',
        'tmp_lahir',
        'tgl_lahir',
        'tgl_lahir_fmt',
        'jenis_kelamin',
        'id_agama',
        'nm_agama',
        'id_kwn',
        'nm_kwn',
        'aktif',
        'alamat',
        'kota',
        'nik',
        'kontak',
        'email_kampus',
        'email_lain',
        'id_jab',
        'nm_jab',
        'id_pangkat',
        'nm_pangkat',
        'sys_password',
        'sys_aktif',
        'a_dosen_wali',
        'golongan'
    ];
    protected $column_search = ['nama', 'mata_kuliah', 'kelas', 'jadwal', 'perkuliahan'];
    protected $order = ['id' => 'DESC'];

    private function getDatatablesQuery($postData, $search = "")
    {
        $this->dt = $this->db->table($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($search) && $search != "") {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $search);
                } else {
                    $this->dt->orLike($item, $search);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        // if ($postData['order']) {
        //     $this->dt->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        // } else if (isset($this->order)) {
        //     $order = $this->order;
        //     $this->dt->orderBy(key($order), $order[key($order)]);
        // }
    }

    public function getDatatables($postData, $search = "")
    {
        $this->dt = $this->db->table($this->table);
        $this->getDatatablesQuery($postData, $search);
        if ($postData['length'] != -1)
            $this->dt->limit($postData['length'], $postData['start']);
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered($postData, $search = "")
    {
        $this->dt = $this->db->table($this->table);
        $this->getDatatablesQuery($postData);
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }

    public function getAllData()
    {
        return $this->findAll();
    }

    public function getDataById($id)
    {
        return $this->find($id);
    }
}
