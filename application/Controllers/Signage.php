<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use App\Helpers\GeneralHelpers;
use App\Controller\BaseController;
use CodeIgniter\HTTP\RequestInterface;

class Signage extends \CodeIgniter\Controller
{
    protected $session;
	protected $request;

    function __construct(RequestInterface $request){
        $this->now      = date('Y-m-d H:i:s');
        $this->http     = new GeneralHelpers;
        $this->request  = $request;
        $this->Data     = [
            'username'  => 'stfi',
            'password'  => 'stfi354!!',
        ];
    }

    public function login_mhs()
	{
		try {
			$data = file_get_contents('php://input');
			$requestData = json_decode($data, true);
			if (isset($requestData['username_mhs']) && isset($requestData['password_mhs'])) {
				$username       = "stfi";
				$password       = "stfi354!!";
				$username_mhs   = $requestData['username_mhs'];
				$password_mhs   = $requestData['password_mhs'];
                $requestdataa   = [
                    'username'      => $username,
                    'password'      => $password,
                    'username_mhs'  => $username_mhs,
                    'password_mhs'  => $password_mhs,
                ];
                $endpoint = 'loginmahasiswa';

                $data       = $this->http->post($endpoint, $requestdataa, 'POST');
                if($data){
                    $response = [
                        'code'     => $data['err_code'],
                        'status'   => $data['message']
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }else{
                    $response = [
                        'code'     => 404,
                        'status'   => 'Tidak Terhubung ke Server'
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
			} else {
				http_response_code(400);
				echo "Data username atau password tidak valid.";
			}
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

    public function riset_dosen()
	{
		try {
            $RisetDosen = new \App\Models\RisetDosenModel();
            $endpoint = 'getrisetdosen';
            
            $response   = $this->http->post($endpoint, $this->Data, 'POST');
            if($response){
                $RisetDosen->truncate();
                foreach ($response['data'] as $item) {
                    $data = [
                        'kode_dosen'        => $item['kode_dosen'], 
                        'nm_dosen'          => $item['nm_dosen'], 
                        'nidn'              => $item['nidn'], 
                        'kd_periode_lkd'    => $item['kd_periode_lkd'], 
                        'tahun_ajaran'      => $item['tahun_ajaran'], 
                        'jenis_karyailmiah' => $item['jenis_karyailmiah'],
                        'judul'             => $item['judul'],
                        'issn'              => $item['issn'],
                        'volume'            => $item['volume'],
                        'nomor'             => $item['nomor'],
                        'tahun'             => $item['tahun'],
                        'created_at'        => $this->now
                    ];
                    $RisetDosen->insert($data);
                }
                $response = [
                    'status'	=> 'Sukses',
                    'code'		=> 200,
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }else{
                $response = [
                    'status'	=> 'Tidak Terhubung ke Server',
                    'code'		=> 404,
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

    public function jurnal_dosen()
	{
		try {
            $JurnalDosen = new \App\Models\JurnalDosenModel();
            $endpoint = 'getjurnaldosen';
            
            $response   = $this->http->post($endpoint, $this->Data, 'POST');
            if($response){
                $JurnalDosen->truncate();
                foreach ($response['data'] as $item) {
                    $data = [
                        'kode_dosen'        => $item['kode_dosen'], 
                        'nm_dosen'          => $item['nm_dosen'], 
                        'nidn'              => $item['nidn'], 
                        'kd_periode_lkd'    => $item['kd_periode_lkd'], 
                        'tahun_ajaran'      => $item['tahun_ajaran'], 
                        'jenis_jurnal'      => $item['jenis_jurnal'],
                        'judul'             => $item['judul'],
                        'issn'              => $item['issn'],
                        'volume'            => $item['volume'],
                        'nomor'             => $item['nomor'],
                        'tahun'             => $item['tahun'],
                        'created_at'        => $this->now
                    ];
                    $JurnalDosen->insert($data);
                }
                $response = [
                    'status'	=> 'Sukses',
                    'code'		=> 200,
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }else{
                $response = [
                    'status'	=> 'Tidak Terhubung ke Server',
                    'code'		=> 404,
                ];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

    // public function getdatadosen(){
    //     $endpoint       = 'getajar';
    //     $data           = $this->http->post($endpoint, $this->Data, 'POST');
    //     foreach ($data['data'] as $item) {
            
    //         $dosen          = new \App\Models\DosenModel();
    //         $table          = "jadwal_praktikum";
    //         $fk             = "nip_dosen";
    //         $id             = $item['kd_dosen'];
    //         $jadwal_dosen   = $dosen->getJadwalDosen($table, $fk, $id);
    //     }

        
    //     // $response = [
    //     //     'code'     => $data['err_code'],
    //     //     'status'   => $data['message'],
    //     //     'data'     => 
    //     // ];
    //     // header('Content-Type: application/json');
    //     // echo json_encode($response);
    // }
    public function getdatadosen(){
        $ajarDosen = new \App\Models\AjarDosenModel();
        $endpoint = 'getajar';
        
        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $ajarDosen->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'id_ajar' => $item['id_ajar'], 
                    'kd_dosen' => $item['kd_dosen'], 
                    'nm_dosen' => $item['nm_dosen'], 
                    'gelar_depan' => $item['gelar_depan'], 
                    'gelar_belakang' => $item['gelar_belakang'], 
                    'aktif' => $item['aktif'], 
                    'a_dosen_wali' => $item['a_dosen_wali'], 
                    'id_mk_kurikulum' => $item['id_mk_kurikulum'], 
                    'id_mk' => $item['id_mk'], 
                    'nm_mk' => $item['nm_mk'], 
                    'kd_prodi_pj' => $item['kd_prodi_pj'],
                    'nm_prodi_pj' => $item['nm_prodi_pj'],
                    'id_kurikulum' => $item['id_kurikulum'],
                    'kd_kurikulum' => $item['kd_kurikulum'],
                    'nm_kurikulum' => $item['nm_kurikulum'],
                    'kd_prodi_kur' => $item['kd_prodi_kur'],
                    'nm_prodi_kur' => $item['nm_prodi_kur'],
                    'kode_mk' => $item['kode_mk'],
                    'sks_tm' => $item['sks_tm'],
                    'sks_prak' => $item['sks_prak'],
                    'sks_prak_lap' => $item['sks_prak_lap'],
                    'sks_sim' => $item['sks_sim'],
                    'a_wajib' => $item['a_wajib'],
                    'a_ta' => $item['a_ta'],
                    'a_teori' => $item['a_teori'],
                    'smt_def' => $item['smt_def']
                ];
                $ajarDosen->insert($data);
            }
            // $table          = "ajar_dosen";
            // $field          = "kd_dosen";
            // $jadwall        = [];
            // $jadwal_dosen   = $ajarDosen->getJadwalDosen($field, $table);
            // foreach ($jadwal_dosen as $key => $value) {
            //     $jadwal_praktikum = $ajarDosen->getJadwalDosen("*", "jadwal_praktikum", "nip_dosen", $value->kd_dosen);
            //     $value->jadwal = $jadwal_praktikum;
            //     $jadwall[] = $value;
            // }

            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    public function getdatabuku(){
        $buku = new \App\Models\BukuModel();
        $endpoint = 'getebook';
        
        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $buku->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'title'  => $item['judul_ebook'], 
                    'cover' => $item['jilid'], 
                    'tanggal' => $item['tanggal_upload'], 
                    'created_at' => $item['tanggal_upload'],
                    'path' => $item['url_file'],
                    'status' => $item['status_boleh_dipinjam'],
                    'kode_ebook' => $item['kode_ebook'],
                    'judul_ebook' => $item['judul_ebook'],
                    'sub_judul_ebook' => $item['sub_judul_ebook'],
                    'pengarang' => $item['pengarang'],
                    'tempat_terbit' => $item['tempat_terbit'],
                    'penerbit' => $item['penerbit'],
                    'tahun_terbit' => $item['tahun_terbit'],
                    'kolase' => $item['kolase'],
                    'jilid' => $item['jilid'],
                    'edisi' => $item['edisi'],
                    'id_kategori_ebook' => $item['id_kategori_ebook'],
                    'nama_kategori_ebook' => $item['nama_kategori_ebook'],
                    'status_boleh_dipinjam' => $item['status_boleh_dipinjam'],
                    'id_klasifikasi' => $item['id_klasifikasi'],
                    'nama_klasifikasi' => $item['nama_klasifikasi'],
                    'id_jenis_ebook' => $item['id_jenis_ebook'],
                    'nama_jenis_ebook' => $item['nama_jenis_ebook'],
                    'judul_ebook_rich' => $item['judul_ebook_rich'],
                    'sub_judul_ebook_rich' => $item['sub_judul_ebook_rich'],
                    'id_bidang' => $item['id_bidang'],
                    'nama_bidang' => $item['nama_bidang'],
                    'username' => $item['username'],
                    'tanggal_upload' => $item['tanggal_upload'],
                    'waktu_upload' => $item['waktu_upload'],
                    'nama_file' => $item['nama_file'],
                    'url_file' => $item['url_file'],
                ];
                $buku->insert($data);
            }

            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function get_mahasiswa(){
        $mahasiswa = new \App\Models\MahasiswaModel();
        $endpoint = 'getmahasiswa';
        
        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $mahasiswa->truncate();
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
            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function get_dosen(){
        $dosen = new \App\Models\DosenModel();
        $endpoint = 'getdosen';

        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $dosen->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'nama' => $item['nm_dosen'], 
                    // 'mata_kuliah' => $item['mata_kuliah'], 
                    // 'jadwal' => $item['jadwal'], 
                    // 'kelas' => $item['kelas'], 
                    // 'perkuliahan' => $item['perkuliahan'], 
                    'status' => $item['kategori_dosen'], 
                    // 'tugas' => $item['tugas'], 
                    // 'create_by' => $item['create_by'], 
                    'create_date' => $this->now, 
                    // 'update_by' => $item['update_by'], 
                    // 'update_date' => $item['update_date'],
                    'kd_dosen' => $item['kd_dosen'],
                    'nm_dosen' => $item['nm_dosen'],
                    'gelar_depan' => $item['gelar_depan'],
                    'gelar_belakang' => $item['gelar_belakang'],
                    'nidn' => $item['nidn'],
                    'a_dosen_homebase' => $item['a_dosen_homebase'],
                    'id_kat_dosen' => $item['id_kat_dosen'],
                    'kategori_dosen' => $item['kategori_dosen'],
                    'tmp_lahir' => $item['tmp_lahir'],
                    'tgl_lahir' => $item['tgl_lahir'],
                    'tgl_lahir_fmt' => $item['tgl_lahir_fmt'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'id_agama' => $item['id_agama'],
                    'nm_agama' => $item['nm_agama'],
                    'id_kwn' => $item['id_kwn'],
                    'nm_kwn' => $item['nm_kwn'],
                    'aktif' => $item['aktif'],
                    'alamat' => $item['alamat'],
                    'kota' => $item['kota'],
                    'nik' => $item['nik'],
                    'kontak' => $item['kontak'],
                    'email_kampus' => $item['email_kampus'],
                    'email_lain' => $item['email_lain'],
                    'id_jab' => $item['id_jab'],
                    'nm_jab' => $item['nm_jab'],
                    'id_pangkat' => $item['id_pangkat'],
                    'nm_pangkat' => $item['nm_pangkat'],
                    'sys_password' => $item['sys_password'],
                    'sys_aktif' => $item['sys_aktif'],
                    'a_dosen_wali' => $item['a_dosen_wali'],
                    'golongan' => $item['golongan']
                ];

                $endpoint1 = 'getajar';
                $endpoint2 = 'getjadwal';
                $this->Data['where'] = [
                    "kd_dosen" => $item['kd_dosen']
                ];

                $response_ajar   = $this->http->post($endpoint1, $this->Data, 'POST');
                $response_jadwal   = $this->http->post($endpoint2, $this->Data, 'POST');
                
                if(!empty($response_ajar['data'])){
                    
                    // $data[]$response_ajar['data'][0]['id_mk_kurikulum']
                    // $data[]$response_ajar['data'][0]['id_mk_kurikulum']
                    // $data[]$response_ajar['data'][0]['id_mk']
                    $data['mata_kuliah'] = $response_ajar['data'][0]['nm_mk'];
                }
                if(!empty($response_jadwal['data'])){
                    $data['kelas'] = $response_jadwal['data'][0]['nm_kelas'];
                    $data['jadwal'] = $response_jadwal['data'][0]['nm_hari'].', '.$response_jadwal['data'][0]['start_time'].'-'.$response_jadwal['data'][0]['end_time'];
                      
                }
                // print_r($response_jadwal);
                $dosen->insert($data);
            }
            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function getjadwal(){
        $jadwal = new \App\Models\KelasModel();
        $endpoint = 'getjadwal';

        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $jadwal->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'nama' => $item['nm_ruangan'], 
                    'no_kelas' => $item['kd_ruangan'], 
                    'matkul' => $item['nm_mk'], 
                    'jam_mulai' => $item['start_time'], 
                    'jam_akhir' => $item['end_time'], 
                    'created_at' => $this->now, 
                    'kd_periode' => $item['kd_periode'],
                    'id_hari' => $item['id_hari'],
                    'nm_hari' => $item['nm_hari'],
                    'id_slot' => $item['id_slot'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'id_kat_slot' => $item['id_kat_slot'],
                    'kd_ruangan' => $item['kd_ruangan'],
                    'nm_ruangan' => $item['nm_ruangan'],
                    'kd_paket_kelas' => $item['kd_paket_kelas'],
                    'kd_kelas' => $item['kd_kelas'],
                    'id_nm_kelas' => $item['id_nm_kelas'],
                    'nm_kelas' => $item['nm_kelas'],
                    'id_ajar' => $item['id_ajar'],
                    'kd_dosen' => $item['kd_dosen'],
                    'nm_dosen' => $item['nm_dosen'],
                    'gelar_depan' => $item['gelar_depan'],
                    'gelar_belakang' => $item['gelar_belakang'],
                    'id_mk_kurikulum' => $item['id_mk_kurikulum'],
                    'id_mk' => $item['id_mk'],
                    'nm_mk' => $item['nm_mk'],
                    'kd_prodi_pj' => $item['kd_prodi_pj'],
                    'nm_prodi_pj' => $item['nm_prodi_pj'],
                    'id_kurikulum' => $item['id_kurikulum'],
                    'nm_kurikulum' => $item['nm_kurikulum'],
                    'kd_prodi_kur' => $item['kd_prodi_kur'],
                    'nm_prodi_kur' => $item['nm_prodi_kur'],
                    'kode_mk' => $item['kode_mk'],
                    'sks_tm' => $item['sks_tm'],
                    'sks_prak' => $item['sks_prak'],
                    'sks_prak_lap' => $item['sks_prak_lap'],
                    'sks_sim' => $item['sks_sim'],
                    'a_wajib' => $item['a_wajib'],
                    'a_teori' => $item['a_teori'],
                    'a_ta' => $item['a_ta'],
                    'smt_def' => $item['smt_def'],
                    'id_jns_kelas' => $item['id_jns_kelas'],
                    'jns_kelas' => $item['jns_kelas']
                ];
                $jadwal->insert($data);
            }
            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function getjadwal_praktikum(){
        $jadwal_praktikum = new \App\Models\PraktikumModel();
        $endpoint = 'getjadwalpraktikum';

        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $jadwal_praktikum->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'ruangan_praktikum' => $item['nama_laboratorium'], 
                    'mata_kuliah_praktikum' => $item['nama_mata_kuliah'], 
                    'nama_dosen' => $item['nama_dosen'],
                    'jam_mulai' => $item['start_time'], 
                    'jam_akhir' => $item['end_time'], 
                    'created_at' => $this->now,
                    'kode_periode_akademik' => $item['kode_periode_akademik'],
                    'id_hari' => $item['id_hari'],
                    'nama_hari' => $item['nama_hari'],
                    'id_slot' => $item['id_slot'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'id_laboratorium' => $item['id_laboratorium'],
                    'nama_laboratorium' => $item['nama_laboratorium'],
                    'kapasitas' => $item['kapasitas'],
                    'id_kelompok' => $item['id_kelompok'],
                    'nama_kelompok' => $item['nama_kelompok'],
                    'kode_paket_kelas' => $item['kode_paket_kelas'],
                    'kode_kelas' => $item['kode_kelas'],
                    'nip_dosen' => $item['nip_dosen'],
                    'nama_dosen' => $item['nama_dosen'],
                    'kode_mata_kuliah' => $item['kode_mata_kuliah'],
                    'nama_mata_kuliah' => $item['nama_mata_kuliah'],
                    'sks' => $item['sks'],
                    'semester_default' => $item['semester_default'],
                    'nm_kelas' => $item['nm_kelas'],
                    'kd_prodi' => $item['kd_prodi'],
                    'nm_prodi' => $item['nm_prodi']
                ];
                $jadwal_praktikum->insert($data);
            }
            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function getdataskripsi(){
        $skripsi = new \App\Models\SkripsiModel();
        $endpoint = 'getskripsi';

        $response   = $this->http->post($endpoint, $this->Data, 'POST');
        if($response){
            $skripsi->truncate();
            foreach ($response['data'] as $item) {
                $data = [
                    'no_induk_buku' => $item['no_induk_buku'],
                    'kode_buku' => $item['kode_buku'],
                    'judul_buku' => $item['judul_buku'],
                    'sub_judul_buku' => $item['sub_judul_buku'],
                    'pengarang' => $item['pengarang'],
                    'tempat_terbit' => $item['tempat_terbit'],
                    'penerbit' => $item['penerbit'],
                    'tahun_terbit' => $item['tahun_terbit'],
                    'kolase' => $item['kolase'],
                    'jilid' => $item['jilid'],
                    'edisi' => $item['edisi'],
                    'id_kategori_buku' => $item['id_kategori_buku'],
                    'nama_kategori_buku' => $item['nama_kategori_buku'],
                    'status_boleh_dipinjam' => $item['status_boleh_dipinjam'],
                    'id_klasifikasi' => $item['id_klasifikasi'],
                    'nama_klasifikasi' => $item['nama_klasifikasi'],
                    'id_sumber_buku' => $item['id_sumber_buku'],
                    'sumber_buku' => $item['sumber_buku'],
                    'id_lokasi' => $item['id_lokasi'],
                    'id_tempat_buku' => $item['id_tempat_buku'],
                    'nama_tempat_buku' => $item['nama_tempat_buku'],
                    'kategori_tempat_buku' => $item['kategori_tempat_buku'],
                    'baris' => $item['baris'],
                    'tanggal_masuk' => $item['tanggal_masuk'],
                    'tanggal_masuk_formated' => $item['tanggal_masuk_formated'],
                    'harga_buku' => $item['harga_buku'],
                    'status_ketersediaan' => $item['status_ketersediaan'],
                    'kondisi_buku' => $item['kondisi_buku'],
                    'status_aktif' => $item['status_aktif'],
                    'no_panggil' => $item['no_panggil'],
                    'created_at' => $this->now
                ];
                $skripsi->insert($data);
            }
            $response = [
                'status'	=> 'Sukses',
                'code'		=> 200,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            $response = [
                'status'	=> 'Tidak Terhubung ke Server',
                'code'		=> 404,
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}