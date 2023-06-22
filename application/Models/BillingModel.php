<?php namespace App\Models;

use CodeIgniter\Model;

class BillingModel extends Model{

    public function getbilling($id = null)
    {
      
      
      $sql = "SELECT *
              FROM t_billing
              where id_rumah = $id
              order by created_at desc";
      
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function getdetailbilling($id = null)
    {
      
      
      $sql = "SELECT *
              FROM t_billing b 
              INNER JOIN t_rumah r on r.id=b.id_rumah 
              where b.id_billing = $id ";
      
      $result = $this->db->query($sql);
      $row = $result->getRowArray();
      foreach ($row as $key => $value) {
        if(!$row[$key]){
          $row[$key] = '-';
        }
      }
      return $row;
    }

    public function getdetailpembayaranbilling($id = null)
    {
      
      
      $sql = "SELECT *
              FROM t_detail_billing b 
              where b.id_billing = $id ";
              
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function save_billing($id = null, $data = null)
    {
        $res = $this->db->table('t_billing')->insert($data);
        return  $this->db->insertID();
    }

    public function save_billing_detail($data = null)
    {
        $res = $this->db->table('t_detail_billing')->insert($data);
        return  $this->db->insertID();
    }

}
