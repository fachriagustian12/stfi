<?php namespace App\Models;

use CodeIgniter\Model;

class StatistikModel extends Model{
    protected $table = 'm_grafik';
    protected $primaryKey = 'id_grafik';
    protected $allowedFields = ['penyesuaian'];

    public function getpenyesuaian()
    {
      
      $builder = $this->db->table('m_grafik');
      $builder->select("*");
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return  $query->getRow();
    }

    public function getdatariwayatpnbp()
    {
      
      $builder = $this->db->table('t_cicilan');
      $builder->select("year(tgl_bayar) as tahun, sum(setoran) as pembayaran");
      $builder->where("(kode_akun = '425123' or kode_akun = '777777')");
      $builder->groupBy("year(tgl_bayar)");
      $query   = $builder->get();
      // echo $this->db->getLastQuery();die;
      return $query->getResult();
    }

    public function getdatapiutangdashboard()
    {

        $sql =   "select 
                  sum(
                      ((`r`.harga_bangunan + `r`.harga_tanah - coalesce(`r`.uang_muka, 0)) - (select coalesce(sum(c.setoran), 0)
                      from t_cicilan c where c.id_rumah=r.id and (c.kode_akun = '425123' or c.kode_akun = '777777')))
                    ) as data 
                  from t_rumah r inner join t_arsip a on a.id_rumah=r.id 
                  where `r`.`status_rumah` <> 0 and (year(now()) - year(a.tgl_kontrak) < 20) and 
                  ((a.no_hak is null or a.no_hak = '') and (a.no_sktl is null or a.no_sktl = ''))";
       
        $result = $this->db->query($sql);
        $row = $result->getRowArray();

        $sql20 =   "select 
                  sum(
                      ((`r`.harga_bangunan + `r`.harga_tanah - coalesce(`r`.uang_muka, 0)) - (select coalesce(sum(c.setoran), 0)
                      from t_cicilan c where c.id_rumah=r.id and (c.kode_akun = '425123' or c.kode_akun = '777777')))
                    ) as data20 
                  from t_rumah r inner join t_arsip a on a.id_rumah=r.id 
                  where `r`.`status_rumah` <> 0 and (year(now()) - year(a.tgl_kontrak) >= 20) and 
                  ((a.no_hak is null or a.no_hak = '') and (a.no_sktl is null or a.no_sktl = ''))";
       
        $result20 = $this->db->query($sql20);
        $row20 = $result20->getRowArray();
        $res = array_merge($row,$row20);       
        return $res;
    }

    public function getdatapembayarandashboard()
    {
       
        $penyesuaian = $this->db->query("select * from m_grafik limit 0, 1")->getRowArray()['penyesuaian'];

        $baseline = $this->db->query("select sum(round(((harga_bangunan + harga_tanah) - coalesce(uang_muka, 0))/lama_angsuran, 0)) as baseline 
                                          from t_rumah r inner join t_arsip a on a.id_rumah=r.id where 
                                          (a.no_hak is null or a.no_hak = '') and (a.no_sktl is null or a.no_sktl = '')
                                          ")->getRowArray()['baseline'];
        $data = array();
        $jumlah = 0;
        for ($i = 0; $i < 12; $i++) {
          $jumlah += $baseline;
          $data[$i]['baseline'] = $jumlah;
        }

        $jumlahbaruperbulan = ($jumlah - $penyesuaian)/12;
        $jumlah = 0;
        for ($i = 0; $i < 12; $i++) {
          $jumlah += $jumlahbaruperbulan;
          $data[$i]['baseline'] = $jumlah;
        }

        $querydata = $this->db->query("select sum(setoran) as pembayaran 
                                        from t_cicilan 
                                        where 
                                        year(now())=year(tgl_bayar) and 
                                        month(now())>=month(tgl_bayar) and (kode_akun = '425123' or kode_akun = '777777')
                                        GROUP BY month(tgl_bayar)")->getResult();
        $jumlah = 0;
        $count = 0;
        foreach ($querydata as $row) {
          $jumlah += $row->pembayaran;
          $data[$count]['pembayaran'] = $jumlah;
          $count++;
        }

        return $data;
    }

    public function getdatastatusdashboard($role = null, $id_provinsi = null)
    {
        
        $data = array();
        $data['status1'] = 0;
        $data['status2'] = 0;
        $data['status3'] = 0;
        $data['status4'] = 0;
        $wilayah = "";
        if($role == 5){
          $wilayah = " and pro.id=".$id_provinsi;
        }
        $querydata = $this->db->query("select 
        cast( CASE 
        WHEN (a.no_hak is not null and a.no_hak <> '') THEN 4 
        WHEN (a.no_sktl is not null and a.no_sktl <> '') THEN 3 
        WHEN (a.no_kontrak is not null and a.no_kontrak <> '') THEN 2 
        ELSE 1 END as SIGNED) as status_sewa
        from t_rumah r 
        LEFT JOIN m_kecamatan kec ON kec.id = r.id_kecamatan
        LEFT JOIN m_kabupaten kab ON kab.id = kec.id_kabupaten
        LEFT JOIN m_provinsi pro ON pro.id = kab.id_provinsi
        inner join t_arsip a on a.id_rumah=r.id where r.status_rumah <> 0 $wilayah")->getResult();

        foreach ($querydata as $row) {
          if($row->status_sewa == 1)
          {
            $data['status1'] += 1;
          }
          elseif($row->status_sewa == 2)
          {
            $data['status2'] += 1;
          }
          elseif($row->status_sewa == 3)
          {
            $data['status3'] += 1;
          }
          elseif($row->status_sewa == 4)
          {
            $data['status4'] += 1;
          }
        }
        
        return $data;
    }

}
