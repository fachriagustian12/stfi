<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'data_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'no_kelas', 'tanggal', 'status', 'matkul', 'jam_mulai', 'jam_akhir', 'created_by', 'created_at', 'updated_by', 'updated_at'];
    protected $column_search = ['nama', 'tanggal', 'status', 'matkul', 'jam_mulai', 'jam_akhir'];
    protected $order = ['id' => 'DESC'];

    private function getDatatablesQuery($postData)
    {
        $this->dt = $this->db->table($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($postData['search']['value'])) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $postData['search']['value']);
                } else {
                    $this->dt->orLike($item, $postData['search']['value']);
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

    public function getDatatables($postData)
    {
        $this->dt = $this->db->table($this->table);
        $this->getDatatablesQuery($postData);
        if ($postData['length'] != -1)
            $this->dt->limit($postData['length'], $postData['start']);
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered($postData)
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
