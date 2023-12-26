<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'data_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'mata_kuliah', 'jadwal', 'kelas', 'perkuliahan', 'status', 'tugas', 'create_by', 'create_date', 'update_by', 'update_date'];
    protected $column_search = ['nama', 'mata_kuliah', 'kelas', 'jadwal', 'perkuliahan'];
    protected $order = ['id' => 'DESC'];

    private function getDatatablesQuery($postData)
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($postData['search']['value'])) {
                if ($i === 0) {
                    $this->db->table($this->table)->groupStart();
                    $this->db->table($this->table)->like($item, $postData['search']['value']);
                } else {
                    $this->db->table($this->table)->orLike($item, $postData['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->table($this->table)->groupEnd();
            }
            $i++;
        }

        // if ($postData['order']) {
        //     $this->db->table($this->table)->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        // } else if (isset($this->order)) {
        //     $order = $this->order;
        //     $this->db->table($this->table)->orderBy(key($order), $order[key($order)]);
        // }
    }

    public function getDatatables($postData)
    {
        $this->getDatatablesQuery($postData);
        if ($postData['length'] != -1)
            $this->db->table($this->table)->limit($postData['length'], $postData['start']);
        $query = $this->db->table($this->table)->get();
        return $query->getResult();
    }

    public function countFiltered($postData)
    {
        $this->getDatatablesQuery($postData);
        return $this->db->table($this->table)->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
