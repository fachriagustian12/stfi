<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'data_perkuliahan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama', 
        'no_kelas', 
        'matkul', 
        'status', 
        'jam_mulai', 
        'jam_akhir', 
        'tanggal', 
        'created_at', 
        'updated_at',
        'created_by', 
        'updated_by', 
        'kd_periode',
        'id_hari',
        'nm_hari',
        'id_slot',
        'start_time',
        'end_time',
        'id_kat_slot',
        'kd_ruangan',
        'nm_ruangan',
        'kd_paket_kelas',
        'kd_kelas',
        'id_nm_kelas',
        'nm_kelas',
        'id_ajar',
        'kd_dosen',
        'nm_dosen',
        'gelar_depan',
        'gelar_belakang',
        'id_mk_kurikulum',
        'id_mk',
        'nm_mk',
        'kd_prodi_pj',
        'nm_prodi_pj',
        'id_kurikulum',
        'nm_kurikulum',
        'kd_prodi_kur',
        'nm_prodi_kur',
        'kode_mk',
        'sks_tm',
        'sks_prak',
        'sks_prak_lap',
        'sks_sim',
        'a_wajib',
        'a_teori',
        'a_ta',
        'smt_def',
        'id_jns_kelas',
        'jns_kelas'
    ];
    protected $column_search = ['nama', 'tanggal', 'status', 'matkul', 'jam_mulai', 'jam_akhir'];
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
        $this->getDatatablesQuery($postData, $search);
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
