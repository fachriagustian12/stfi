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


}
