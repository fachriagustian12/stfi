<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
    protected $table = 'm_user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'username','password','id_role', 'status', 'create_date'];

    public function getWhereis($where = null)
    {

      $builder = $this->db->table('m_user');
      $builder->select("m_user.*, m_role.role");
      $builder->join('m_role', 'm_role.id = m_user.id_role', 'INNER');
      // $builder->join('m_provinsi', 'm_provinsi.id = m_user.id_provinsi', 'INNER');
      $query   = $builder->getWhere($where);
      
      return  $query->getRow();
    }

    public function getWhere($where = null, $param = null)
    {
      
      if($param){
        $builder = $this->db->table('m_user_simponi');
        $builder->select("m_user_simponi.*, m_provinsi.id as id_provinsi, m_provinsi.provinsi");
        // $builder->join('m_provinsi', 'm_provinsi.id = m_user_simponi.id_provinsi', 'INNER');
      }else{
        $builder = $this->db->table('m_user');
        $builder->select("m_user.*, m_role.role");
        $builder->join('m_role', 'm_role.id = m_user.id_role', 'INNER');
        // $builder->join('m_provinsi', 'm_provinsi.id = m_user.id_provinsi', 'INNER');
      }
      $query   = $builder->getWhere($where);
     
      return  $query;
    }

    public function getUsers($param = null)
    {

        $builder = $this->db->table('m_user');
        $builder->select("m_user.*, m_role.role");
        $builder->join('m_role', 'm_role.id = m_user.id_role', 'INNER');
        $query   = $builder->get();
      
      // echo $this->db->getLastQuery();
      return $query->getResult();
    }

    public function getprovinsi($id = null)
    {
      $builder = $this->db->table('m_provinsi');
      $builder->select("*");
      $builder->orderBy('provinsi', 'ASC');
      $builder->where('status <> 0');
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }

    public function getkabupaten($id = null)
    {
      $builder = $this->db->table('m_kabupaten');
      $builder->select("*");
      $builder->orderBy('kabupaten', 'ASC');
      $builder->where('status <> 0');
      $builder->where("id_provinsi = $id");
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }

    public function getkecamatan($id = null)
    {
      $builder = $this->db->table('m_kecamatan');
      $builder->select("*");
      $builder->orderBy('kecamatan', 'ASC');
      $builder->where('status <> 0');
      $builder->where("id_kabupaten = $id");
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }

    public function getlembaga($id = null)
    {
      $builder = $this->db->table('m_lembaga');
      $builder->select("*");
      $builder->where('status <> 0');
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }

    public function getrole($id = null)
    {
      $builder = $this->db->table('m_role');
      $builder->select("*");
      $builder->where('status <> 0');
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }


    public function updateIsLogin($id, $data)
    {
      $builder = $this->db->table('users');
      $query   = $builder->where('user_id', $id);
      $query->update($data);
      // echo $this->db->getLastQuery();

      return true;
    }

    public function insertUser($data = null)
    {
        $res = $this->db->table('m_user')->insert($data);
        return  $res;
    }

    public function updateUser($id = null, $data = null)
    {
      
        $res = $this->db->table('m_user')->where('id', $id)->update($data);
        //  echo $this->db->getLastQuery();die;
        return  $res;
    }

    public function deleteUser($id = null)
    {
        $res = $this->db->table('m_user')->where('id', $id)->delete();
        return  $res;
    }

    public function getData($table = null, $id = null)
    {
        $builder = $this->db->table("data_$table");
        $builder->select("*");
        if($id){
          $query  = $builder->getWhere(['id' => $id]);
          return $query->getRow();
        }else{
          $query   = $builder->get();
          return $query->getResult();
        }
    }
    
    public function getDosen($id = null)
    {
        $builder = $this->db->table("data_dosen");
        $builder->select("*");
        if($id){
          $query  = $builder->getWhere(['kd_dosen' => $id]);
          return $query->getRow();
        }else{
          $query   = $builder->get();
          return $query->getResult();
        }
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

    public function deleteData($id = null, $table = null)
    {
        $res = $this->db->table("data_$table")->where('id', $id)->delete();
        return  $res;
    }

}
