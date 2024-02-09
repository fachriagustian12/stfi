<?php

namespace App\Models;

use CodeIgniter\Model;

class AjarDosenModel extends Model
{
    protected $table = 'data_ajar_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_ajar', 
        'kd_dosen', 
        'nm_dosen', 
        'gelar_depan', 
        'gelar_belakang', 
        'aktif', 
        'a_dosen_wali', 
        'id_mk_kurikulum', 
        'id_mk', 
        'nm_mk', 
        'kd_prodi_pj',
        'nm_prodi_pj',
        'id_kurikulum',
        'kd_kurikulum',
        'nm_kurikulum',
        'kd_prodi_kur',
        'nm_prodi_kur',
        'kode_mk',
        'sks_tm',
        'sks_prak',
        'sks_prak_lap',
        'sks_sim',
        'a_wajib',
        'a_ta',
        'a_teori',
        'smt_def'
    ];
    protected $order = ['id' => 'DESC'];

    public function getJadwalDosen($field = null, $table = null, $fk = null, $id = null)
    {
        $builder = $this->db->table("data_$table");
        $builder->select($field);
        if($id){
          $query  = $builder->getWhere([$fk => $id]);
          return $query->getResult();
        }else{
          $query   = $builder->get();
          return $query->getResult();
        }
    }

    public function JoinTable($table = null){
      $builder = $this->db->table("data_$table");
      $builder->select($field);
      // SELECT pegawai.nama AS nama_pegawai, departemen.nama AS nama_departemen
      // FROM pegawai
      // LEFT JOIN departemen ON pegawai.id_departemen = departemen.id;
    }
}
