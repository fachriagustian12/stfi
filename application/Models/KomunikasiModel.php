<?php namespace App\Models;

use CodeIgniter\Model;

class KomunikasiModel extends Model{
    protected $table = 't_tiket';
    protected $primaryKey = 'id_tiket';
    protected $allowedFields = ['judul_tiket','isi_tiket','status_tiket'];

    public function gettiket($iduser = null, $role = null, $id_tiket = null )
    {
        
        $builder = $this->db->table('t_tiket');
        $builder->select("t_tiket.*, u1.username as nama1, u2.username as nama2 ");
        $builder->join('m_user u1', 't_tiket.created_by = u1.id', 'INNER');
        $builder->join('m_user u2', 't_tiket.updated_by = u2.id', 'INNER');
        if($iduser){
          $builder->where("t_tiket.created_by = $id");
        }

        if($id_tiket){
          $builder->where("t_tiket.id_tiket = $id_tiket");
        }
        $builder->where('t_tiket.status_tiket <> 0');
        $builder->orderBy('t_tiket.updated_at', 'DESC');
        $builder->orderBy('t_tiket.created_at', 'DESC');
        $query   = $builder->get();
        if($id_tiket){
          $result = $query->getRow();
        }else{
          $result = $query->getResult();
        }
        
        return $result;
    }

    public function getdiskusitiket($id_tiket = null )
    {
        
        $builder = $this->db->table('t_reply_tiket');
        $builder->select("t_reply_tiket.*, u1.username as nama1, u2.username as nama2 ");
        $builder->join('m_user u1', 't_reply_tiket.created_by = u1.id', 'INNER');
        $builder->join('m_user u2', 't_reply_tiket.updated_by = u2.id', 'INNER');
        $builder->where("t_reply_tiket.id_tiket = $id_tiket");
        $builder->where('t_reply_tiket.status_reply <> 0');
        $query   = $builder->get();

        return  $query->getResult();
    }

    public function insertReply($data = null)
    {
        $res = $this->db->table('t_reply_tiket')->insert($data);
        return  $res;
    }

    public function updateTiket($id = null, $data = null)
    {
        $res = $this->db->table('t_tiket')->where('id_tiket', $id)->update($data);
        return  $res;
    }

    public function deletekomunikasi($id = null)
    {
        $res = $this->db->table('t_tiket')->where('id_tiket', $id)->delete();
        return  $res;
    }

}
