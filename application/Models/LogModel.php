<?php namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model{
    protected $table = 't_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tgl','username','keterangan'];

    public function getLogs($length = null, $start = null, $search = null)
    {
      
      $builder = $this->db->table('t_logs');
      $builder->select("*");
      if($search['value']){
          $builder->like('username', $search['value']);
          $builder->orLike('keterangan', $search['value']);
      }
      $builder->limit($length, $start);
      $builder->orderBy('id', 'DESC');
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return  $query->getResult();
    }

    public function countLogs($search = null)
    {
      $builder = $this->db->table('t_logs');
      $builder->select("*");
      if($search['value']){
        $builder->like('username', $search['value']);
        $builder->orLike('keterangan', $search['value']);
      }
      
      $query   = $builder->countAllResults();
      // echo $this->db->getLastQuery();die;
      return  $query;
    }

    public function addlog($data = null){
      $builder = $this->db->table('log_aktifitas')->insert($data);
      return $builder;
    }

    public function getlog($param = null)
    {
        $builder = $this->db->table('log_aktifitas');
        $builder->select("id, tanggal, aktifitas, keterangan,
	(select nama from data_mahasiswa WHERE nim = log_aktifitas.nama) as nama");
        $query   = $builder->get();
      return $query->getResult();
    }
}
