<?php
namespace App\Models;

use CodeIgniter\Model;

class DataModel extends Model {

    public function getData($table = null) {
        if ($table) {
            $this->table = $table;
        }

        if ($this->table == '') {
            throw new \Exception('Table name must be specified.');
        }
        
        return $this->findAll();
    }

    public function joinDosen($id = null)
    {
        $builder = $this->db->table("data_perkuliahan");
        $builder->select("
          data_perkuliahan.id,
          data_perkuliahan.nama, data_perkuliahan.no_kelas, 
          data_perkuliahan.matkul, data_perkuliahan.status, 
          data_perkuliahan.jam_mulai, data_perkuliahan.jam_akhir, 
          data_perkuliahan.nm_hari, data_perkuliahan.nm_kelas, 
          data_dosen.nm_dosen");
        $builder->join('data_dosen', 'data_perkuliahan.kd_dosen = data_dosen.kd_dosen', 'INNER');
        if($id){
          $query  = $builder->getWhere(['kd_dosen' => $id]);
          return $query->getRow();
        }else{
          $query   = $builder->get();
          return $query->getResult();
        }
    }

    public function getDosenPraktik($id = null)
    {

      $builder = $this->db->table("data_jadwal_praktikum");
      $builder->select("
      data_jadwal_praktikum.id,
      data_jadwal_praktikum.ruangan_praktikum,
			data_jadwal_praktikum.mata_kuliah_praktikum,
      data_jadwal_praktikum.nama_kelompok,
			data_jadwal_praktikum.jam_mulai,
      data_jadwal_praktikum.jam_akhir,
			data_jadwal_praktikum.nama_hari,
      data_dosen.nm_dosen,data_dosen.kd_dosen");
      $builder->join('data_dosen', 'data_jadwal_praktikum.nip_dosen = data_dosen.kd_dosen', 'INNER');
      if($id){
        $query  = $builder->getWhere(['data_jadwal_praktikum.id' => $id]);
        return $query->getRow();
      }else{
        $query   = $builder->get();
        return $query->getResult();
      }
    }
}
