<?php namespace App\Models;

use CodeIgniter\Model;

class CicilanModel extends Model{
    protected $table = 't_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tgl','username','keterangan'];

    public function getcicilan($length = null, $start = null, $search = null, $req = null)
    {
      $stt = $req['status'];
      $where = '';
      if($req['hdno']){
        $where .= " and r.hdno like '%".$req['hdno']."%'";
      }

      if($req['alamat']){
        $where .= " and r.alamat like '%".$req['alamat']."%'";
      }

      if($req['nama']){
        $where .= " and r.nama like '%".$req['nama']."%'";
      }

      if($req['provinsi']){
        $where .= " and pro.id = '".$req['provinsi']."'";
      }

      if($req['kabupaten']){
        $where .= " and kab.id = '".$req['kabupaten']."'";
      }

      if($req['kecamatan']){
        $where .= " and kec.id = '".$req['kecamatan']."'";
      }

      if($req['lembaga']){
        $where .= " and l.id = '".$req['lembaga']."'";
      }
      
      $sql = "SELECT
            r.id,
            r.hdno,
            r.nama,
            r.alamat,
            (
              (r.harga_bangunan + r.harga_tanah) - coalesce(r.uang_muka, 0)
            ) as harga,
            (
              (select coalesce(sum(c.setoran), 0) from t_cicilan c where c.id_rumah=r.id and (c.kode_akun='425123' or c.kode_akun='777777'))
            ) as pembayaran,
            (
              (r.harga_bangunan + r.harga_tanah - coalesce(r.uang_muka, 0) - (select coalesce(sum(c.setoran), 0) from t_cicilan c where c.id_rumah=r.id and (c.kode_akun='425123' or c.kode_akun='777777')))
            ) as sisa,
            IF(
              (a.no_hak <> '' or a.no_hak is not null) or (a.no_sktl <> '' or a.no_sktl is not null), (((r.harga_bangunan + r.harga_tanah - coalesce(r.uang_muka, 0)) / coalesce(r.lama_angsuran, 240)) * 12
              ), 0
            ) as pnbp
            FROM t_rumah AS r 
            INNER JOIN m_lembaga AS l ON l.id = r.id_lembaga 
            INNER JOIN t_arsip as a ON a.id_rumah=r.id
            INNER JOIN m_tipe t ON r.id_tipe = t.id
            INNER JOIN m_konstruksi k ON r.id_konstruksi = k.id 
            LEFT JOIN m_kecamatan as kec ON kec.id=r.id_kecamatan
            LEFT JOIN m_kabupaten as kab ON kab.id=kec.id_kabupaten
            LEFT JOIN m_provinsi as pro ON pro.id=kab.id_provinsi
            WHERE r.status_rumah = '$stt' $where
            limit $start, $length
            ";
      
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function countcicilan($search = null, $req = null)
    {

      $stt = $req['status'];
      $where = '';
      if($req['hdno']){
        $where .= " and r.hdno like '%".$req['hdno']."%'";
      }

      if($req['alamat']){
        $where .= " and r.alamat like '%".$req['alamat']."%'";
      }

      if($req['nama']){
        $where .= " and r.nama like '%".$req['nama']."%'";
      }

      if($req['provinsi']){
        $where .= " and pro.id = '".$req['provinsi']."'";
      }

      if($req['kabupaten']){
        $where .= " and kab.id = '".$req['kabupaten']."'";
      }

      if($req['kecamatan']){
        $where .= " and kec.id = '".$req['kecamatan']."'";
      }

      if($req['lembaga']){
        $where .= " and l.id = '".$req['lembaga']."'";
      }

      $sql = "SELECT
            count(r.id) as jumlah
            FROM t_rumah AS r 
            INNER JOIN m_lembaga AS l ON l.id = r.id_lembaga 
            INNER JOIN t_arsip as a ON a.id_rumah=r.id
            INNER JOIN m_tipe t ON r.id_tipe = t.id
            INNER JOIN m_konstruksi k ON r.id_konstruksi = k.id 
            LEFT JOIN m_kecamatan as kec ON kec.id=r.id_kecamatan
            LEFT JOIN m_kabupaten as kab ON kab.id=kec.id_kabupaten
            LEFT JOIN m_provinsi as pro ON pro.id=kab.id_provinsi
            WHERE r.status_rumah = '$stt' $where
            ";
      
      $result = $this->db->query($sql);
      $row = $result->getRow();
      return $row;
    }

    public function getdetailrumahcicilan($id = null) {
      $sql = "
              SELECT
              t_rumah.id,
              t_rumah.hdno,
              t_rumah.alamat,
              t_rumah.nama,
              t_rumah.harga_bangunan,
              t_rumah.harga_tanah,
              t_rumah.uang_muka,
              t_rumah.lama_angsuran,
              m_lembaga.lembaga,
              m_provinsi.provinsi,
              m_kabupaten.kabupaten,
              (t_rumah.harga_bangunan + t_rumah.harga_tanah - coalesce(t_rumah.uang_muka, 0) - (select coalesce(sum(c.setoran), 0) from t_cicilan c where c.id_rumah=t_rumah.id and (c.kode_akun='425123' or c.kode_akun='777777'))) as sisa_piutang
              FROM
              t_rumah
              LEFT OUTER JOIN m_lembaga ON t_rumah.id_lembaga = m_lembaga.id
              LEFT OUTER JOIN m_kecamatan ON t_rumah.id_kecamatan = m_kecamatan.id
              LEFT OUTER JOIN m_kabupaten ON m_kabupaten.id = m_kecamatan.id_kabupaten
              LEFT OUTER JOIN m_provinsi ON m_provinsi.id = m_kabupaten.id_provinsi
              where t_rumah.id = $id";

      $result = $this->db->query($sql);
      $row = $result->getRow();
      return $row;
    }

    public function getdetailcicilanangsuran($id = null) {
      $sql = "
        SELECT *
        FROM t_cicilan
        where (kode_akun <> '425828' or kode_akun is null) and id_rumah = $id
      ";
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function getdetailcicilandenda($id = null) {
      $sql = "SELECT *
              FROM t_cicilan
              where kode_akun = '425828' and id_rumah = $id ";
      $result = $this->db->query($sql);
      $row = $result->getResult();
      return $row;
    }

    public function ubahpembayaran($id_rumah, $id, $data)
    {
      $builder = $this->db->table('t_cicilan');
      $query   = $builder->where('id', $id);
      $query->update($data);
      // echo $this->db->getLastQuery();die;

      return true;
    }

    public function pembayaran($data = null)
    {
        $res = $this->db->table('t_cicilan')->insert($data);
        return  $res;
    }

}
