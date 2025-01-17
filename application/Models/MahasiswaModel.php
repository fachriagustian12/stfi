<?php 
namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model{
    protected $table = 'data_mahasiswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'nama', 
        'npm', 
        'semester', 
        'prodi', 
        'status_mahasiswa', 
        'status_perwalian', 
        'create_date', 
        'update_date', 
        'create_by', 
        'update_by',
        'nim',
        'id_mhs',
        'tgl_masuk',
        'tgl_masuk_fmt',
        'id_stat_masuk',
        'jns_masuk',
        'ket_masuk',
        'nm_stat_masuk',
        'kd_prodi',
        'nm_prodi',
        'id_angkatan',
        'id_jns_keluar',
        'ket_keluar',
        'tgl_keluar',
        'tgl_keluar_fmt',
        'sks_diakui',
        'nm_pt_asal',
        'nm_prodi_asal',
        'no_ijazah',
        'sk_yudisium',
        'jalur_skripsi',
        'judul_skripsi',
        'tgl_sk_yudisium',
        'tgl_sk_yudisium_fmt',
        'ipk',
        'id_stat_mhs',
        'status_mhs',
        'sys_password',
        'sys_aktif',
        'nm_mhs',
        'jenis_kelamin',
        'tmp_lahir',
        'tgl_lahir',
        'tgl_lahir_fmt',
        'id_agama',
        'nm_agama',
        'id_kwn',
        'nm_kwn',
        'alamat',
        'kota',
        'id_wil',
        'nm_ayah',
        'nm_ibu',
        'job_ayah',
        'job_ibu',
        'nik',
        'email_kampus',
        'email_lain',
        'kontak',
        'kd_dosen_wali',
        'nm_dosen_wali',
        'nm_job_ayah',
        'nm_job_ibu',
        'large_kontak',
        'nisn',
        'npwp',
        'jalan',
        'dusun',
        'rt',
        'rw',
        'kelurahan',
        'zip',
        'kps',
        'id_alat_transport',
        'nm_alat_transport',
        'id_jenis_tinggal',
        'nm_jenis_tinggal'
    ];

    public function insertMhs($data = null)
    {
        $res = $this->db->table('data_mahasiswa')->insert($data);
        return  $res;
    }

    public function deleteData($id = null, $table = null)
    {
        $res = $this->db->table("data_$table")->where('id', $id)->delete();
        return  $res;
    }
}
