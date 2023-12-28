<?php

namespace App\Models;

use CodeIgniter\Model;

class PraktikumModel extends Model
{
    protected $table = 'data_jadwal_praktikum';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_dosen', 'ruangan_praktikum', 'tanggal', 'status', 'mata_kuliah_praktikum', 'jam_mulai', 'jam_akhir', 'created_by', 'created_at', 'updated_by', 'updated_at'];
    protected $column_search = ['data_dosen.nama','data_jadwal_praktikum.ruangan_praktikum', 'data_jadwal_praktikum.tanggal', 'data_jadwal_praktikum.status', 'data_jadwal_praktikum.mata_kuliah_praktikum', 'data_jadwal_praktikum.jam_mulai', 'data_jadwal_praktikum.jam_akhir'];
    protected $order = ['id' => 'DESC'];

    private function getDatatablesQuery($postData, $search = "")
    {
        $this->dt = $this->db->table($this->table);
        $i = 0;
        $this->dt->select('data_jadwal_praktikum.*, data_dosen.nama, data_dosen.id');
        $this->dt->join('data_dosen', 'data_jadwal_praktikum.nama_dosen = data_dosen.id', 'left');
        // $this->dt->join('data_dosen', 'data_dosen.id = data_jadwal_praktikum.nama_dosen', 'LEFT');
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
}
