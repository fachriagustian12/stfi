<?php

namespace App\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;

class MhsModel extends Model
{
    protected $table = 'data_mahasiswa';
    protected $column_order = ['id', 'nama', 'npm', 'semester', 'prodi', 'status_mahasiswa'];
    protected $column_search = ['nama', 'npm', 'prodi'];
    protected $order = ['id' => 'DESC'];
    protected $request;
    protected $db;
    protected $dt;

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

    public function getDatatables($postData, $search ="")
    {
        $this->dt = $this->db->table($this->table);
        $this->getDatatablesQuery($postData, $search);
        if ($postData['length'] != -1)
            $this->dt->limit($postData['length'], $postData['start']);
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered($postData, $search ="")
    {
        $this->dt = $this->db->table($this->table);
        $this->getDatatablesQuery($postData, $search);
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
