<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Helpers\GeneralHelpers;
use App\Controller\BaseController;
use CodeIgniter\HTTP\RequestInterface;

class Signage extends \CodeIgniter\Controller
{
    protected $session;
	protected $request;

    function __construct(RequestInterface $request){
        $this->now = date('Y-m-d H:i:s');
    }

    public function get_mahasiswa(){
        $mahasiswa = new \App\Models\MahasiswaModel();
        $mahasiswa->truncate();
        $http       = new GeneralHelpers;
        
        $endpoint   = 'getmahasiswa';
        $Data       = [
            'username'  => 'stfi',
            'password'  => 'stfi354!!',
        ];

        
        $response   = $http->post($endpoint, $Data, 'POST');
        foreach ($response['data'] as $item) {
            $data = [
                'id' => $item['id_mhs'],
                'nama' => $item['nm_mhs'],
                'prodi' => $item['nm_prodi'],
                'status_mahasiswa' => $item['status_mhs'],
                'create_date' 	=> $this->now,
                'nim' => $item['nim'],
                'id_mhs' => $item['id_mhs'],
                'tgl_masuk' => $item['tgl_masuk'],
                'tgl_masuk_fmt' => $item['tgl_masuk_fmt'],
                'id_stat_masuk' => $item['id_stat_masuk'],
                'jns_masuk' => $item['jns_masuk'],
                'ket_masuk' => $item['ket_masuk'],
                'nm_stat_masuk' => $item['nm_stat_masuk'],
                'kd_prodi' => $item['kd_prodi'],
                'nm_prodi' => $item['nm_prodi'],
                'id_angkatan' => $item['id_angkatan'],
                'id_jns_keluar' => $item['id_jns_keluar'],
                'ket_keluar' => $item['ket_keluar'],
                'tgl_keluar' => $item['tgl_keluar'],
                'tgl_keluar_fmt' => $item['tgl_keluar_fmt'],
                'sks_diakui' => $item['sks_diakui'],
                'nm_pt_asal' => $item['nm_pt_asal'],
                'nm_prodi_asal' => $item['nm_prodi_asal'],
                'no_ijazah' => $item['no_ijazah'],
                'sk_yudisium' => $item['sk_yudisium'],
                'jalur_skripsi' => $item['jalur_skripsi'],
                'judul_skripsi' => $item['judul_skripsi'],
                'tgl_sk_yudisium' => $item['tgl_sk_yudisium'],
                'tgl_sk_yudisium_fmt' => $item['tgl_sk_yudisium_fmt'],
                'ipk' => $item['ipk'],
                'id_stat_mhs' => $item['id_stat_mhs'],
                'status_mhs' => $item['status_mhs'],
                'sys_password' => $item['sys_password'],
                'sys_aktif' => $item['sys_aktif'],
                'nm_mhs' => $item['nm_mhs'],
                'jenis_kelamin' => $item['jenis_kelamin'],
                'tmp_lahir' => $item['tmp_lahir'],
                'tgl_lahir' => $item['tgl_lahir'],
                'tgl_lahir_fmt' => $item['tgl_lahir_fmt'],
                'id_agama' => $item['id_agama'],
                'nm_agama' => $item['nm_agama'],
                'id_kwn' => $item['id_kwn'],
                'nm_kwn' => $item['nm_kwn'],
                'alamat' => $item['alamat'],
                'kota' => $item['kota'],
                'id_wil' => $item['id_wil'],
                'nm_ayah' => $item['nm_ayah'],
                'nm_ibu' => $item['nm_ibu'],
                'job_ayah' => $item['job_ayah'],
                'job_ibu' => $item['job_ibu'],
                'nik' => $item['nik'],
                'email_kampus' => $item['email_kampus'],
                'email_lain' => $item['email_lain'],
                'kontak' => $item['kontak'],
                'kd_dosen_wali' => $item['kd_dosen_wali'],
                'nm_dosen_wali' => $item['nm_dosen_wali'],
                'nm_job_ayah' => $item['nm_job_ayah'],
                'nm_job_ibu' => $item['nm_job_ibu'],
                'large_kontak' => $item['large_kontak'],
                'nisn' => $item['nisn'],
                'npwp' => $item['npwp'],
                'jalan' => $item['jalan'],
                'dusun' => $item['dusun'],
                'rt' => $item['rt'],
                'rw' => $item['rw'],
                'kelurahan' => $item['kelurahan'],
                'zip' => $item['zip'],
                'kps' => $item['kps'],
                'id_alat_transport' => $item['id_alat_transport'],
                'nm_alat_transport' => $item['nm_alat_transport'],
                'id_jenis_tinggal' => $item['id_jenis_tinggal'],
                'nm_jenis_tinggal' => $item['nm_jenis_tinggal']
            ];
            $mahasiswa->insert($data);
        }
    }
}