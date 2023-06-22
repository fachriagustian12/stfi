<?php namespace App\Models;

use CodeIgniter\Model;

class ComponentModel extends Model{
    protected $table = 't_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tgl','username','keterangan'];

    public function getBox($length = null, $start = null, $search = null)
    {

      if($length){
        $limit = "LIMIT $start, $length";
      }

        $sql =   "(select lokasi_sk as box from t_arsip where lokasi_sk <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_sk like '%".$search['value']."%'" : '')." group by lokasi_sk $limit)";
        $sql .=  "union";
        $sql .=  "(select lokasi_sip as box from t_arsip where lokasi_sip <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_sip like '%".$search['value']."%'" : '')." group by lokasi_sip $limit)";
        $sql .=  "union";
        $sql .=  "(select lokasi_pengalihanhak as box from t_arsip where lokasi_pengalihanhak <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_pengalihanhak like '%".$search['value']."%'" : '')." group by lokasi_pengalihanhak $limit)";
        $sql .=  "union";
        $sql .=  "(select lokasi_kontrak as box from t_arsip where lokasi_kontrak <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_kontrak like '%".$search['value']."%'" : '')." group by lokasi_kontrak $limit)";
        $sql .=  "union";
        $sql .=  "(select lokasi_sktl as box from t_arsip where lokasi_sktl <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_sktl like '%".$search['value']."%'" : '')." group by lokasi_sktl $limit)";
        $sql .=  "union";
        $sql .=  "(select lokasi_hak as box from t_arsip where lokasi_hak <> '00.00.00.00.00' ".($search['value'] ? "and lokasi_hak like '%".$search['value']."%'" : '')." group by lokasi_hak $limit)";
        
        $result = $this->db->query($sql);
        $row = $result->getResult();
        return $row;
    }

    public function countBox($search = null)
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

    public function getBoxdetail($box = null)
    {

        $sql =   "select r.hdno, a.* from t_arsip a inner join t_rumah r on r.id=a.id_rumah where 
                  a.lokasi_sk = '$box' or
                  a.lokasi_sip = '$box' or
                  a.lokasi_pengalihanhak = '$box' or
                  a.lokasi_kontrak = '$box' or
                  a.lokasi_sktl = '$box' or
                  a.lokasi_hak = '$box'";
       
        $result = $this->db->query($sql);
        $row = $result->getResult();
        return $row;
    }


}
