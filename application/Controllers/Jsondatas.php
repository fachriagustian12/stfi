<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;
use App\Controllers\BaseController as ControllersBaseController;
use App\Models\FrontModel;
use App\Models\MhsModel;
use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\PraktikumModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\Request;
use App\Controllers\Services;

class Jsondatas extends ControllersBaseController
{

    public function getMhs()
    {
        $request = $this->request;
        $mhsmodel = new \App\Models\MhsModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $mhsmodel->getDatatables($request->getPost(), $request->getPost('search')['value']);
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->nim;
                $row[] = $list->semester;
                $row[] = $list->prodi;
                $row[] = $list->status_mhs == 'LULUS' || $list->status_mhs == 'AKTIF' ? '<span class="badge bg-success p-2"> '.$list->status_mhs.' </span>' : '<span class="badge bg-danger p-2"> '.$list->status_mhs.' </span>';
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $mhsmodel->countAll(),
                'recordsFiltered' => $mhsmodel->countFiltered($request->getPost(), $request->getPost('search')['value']),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }

    public function getKelas()
    {
        $request = $this->request;
        $mhsmodel = new \App\Models\KelasModel();

        // if ($request->getMethod(true) === 'POST') {
        //     $lists = $mhsmodel->getDatatables($request->getPost(), $request->getPost('search')['value']);
        //     $data = [];
        //     $no = $request->getPost('start');

        //     foreach ($lists as $list) {
        //         $no++;
        //         $row = [];
        //         $row[] = $no;
        //         $row[] = $list->nama;
        //         $row[] = $list->no_kelas;
        //         $row[] = $list->matkul;
        //         $row[] = $list->nm_dosen;
        //         $row[] = $list->jam_mulai;
        //         $row[] = $list->jam_akhir;
        //         $row[] = $list->nm_hari;
        //         $row[] = $list->status == 'Ada' ? '<span class="badge bg-success p-2">Ada</span>' : '<span class="badge bg-secondary p-2">Ditiadakan</span>';
        //         $data[] = $row;
        //     }

        //     $output = [
        //         'draw' => $request->getPost('draw'),
        //         'recordsTotal' => $mhsmodel->countAll(),
        //         'recordsFiltered' => $mhsmodel->countFiltered($request->getPost(), $request->getPost('search')['value']),
        //         'data' => $data
        //     ];

        $lists = $mhsmodel->getAllData();

        $output = [
            'code' => 200,
            'data' => $lists
        ];

        echo json_encode($output);
        // }
    }
    public function getDsn()
    {
        $request = $this->request;
        $dsnmodel = new \App\Models\DosenModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $dsnmodel->getDatatables($request->getPost(), $request->getPost('search')['value']);
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->nm_jab;
                $row[] = $list->nm_pangkat;
                $row[] = $list->kategori_dosen;
                $row[] = $list->alamat;
                $row[] = $list->kontak;
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $dsnmodel->countAll(),
                'recordsFiltered' => $dsnmodel->countFiltered($request->getPost(), $request->getPost('search')['value']),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }
    public function getPraktikum()
    {
        $request = $this->request;
        $dsnmodel = new \App\Models\PraktikumModel();

        // if ($request->getMethod(true) === 'POST') {
        //     $lists = $dsnmodel->getDatatables($request->getPost(), $request->getPost('search')['value']);
        //     $data = [];
        //     $no = $request->getPost('start');

        //     foreach ($lists as $list) {
        //         $no++;
        //         $row = [];
        //         $row[] = $no;
        //         $row[] = $list->ruangan_praktikum;
        //         $row[] = $list->mata_kuliah_praktikum;
        //         $row[] = $list->nama_dosen;
        //         $row[] = $list->jam_mulai . ' WIB';
        //         $row[] = $list->jam_akhir . ' WIB';
        //         $row[] = $list->nama_hari;
        //         $row[] = $list->status == 'Ada' ? '<span class="badge bg-success p-2">Ada</span>' : '<span class="badge bg-secondary p-2">Ditiadakan</span>';
        //         $data[] = $row;
        //     }

        $lists = $dsnmodel->getAllData();

        $output = [
            'code' => 200,
            'data' => $lists
        ];

        echo json_encode($output);
        // }
    }

    public function getbuku()
    {
        $request = $this->request;
        $model = new \App\Models\BukuModel();

        $get = $this->request->getVar('search');
        
        if ($get != "" && $get != NULL) {
            $results = $model->like('title', $get)->paginate(5, 'new_pagination');
        } else {
            $results = $model->paginate(5, 'new_pagination');
        }
        
        // Menampilkan link pagination
        $pager = $model->pager;
        $links = $pager->links('new_pagination', 'new_pagination');

        $output = array(
            'code' => 200,
            'result' => $results,
            'pager' => $pager,
            'links' => $links
        );
        echo json_encode($output);
    }

    public function getSkripsi()
    {
        $request = $this->request;
        $model = new \App\Models\SkripsiModel();

        $get = $this->request->getVar('search');

        if ($get != "" && $get != NULL) {
            $results = $model->like('judul_buku', $get)->paginate(5, 'new_pagination');
        } else {
            $results = $model->paginate(5, 'new_pagination');
        }

        // Menampilkan link pagination
        $pager = $model->pager;
        $links = $pager->links('new_pagination', 'new_pagination');

        $output = array(
            'code' => 200,
            'result' => $results,
            'pager' => $pager,
            'links' => $links
        );
        echo json_encode($output);
    }

    public function getJurnal()
    {
        $request = $this->request;
        $model = new \App\Models\JurnalDosenModel();

        $get = $this->request->getVar('search');

        if ($get != "" && $get != NULL) {
            $results = $model->like('judul', $get)->paginate(5, 'new_pagination');
        } else {
            $results = $model->paginate(5, 'new_pagination');
        }

        // Menampilkan link pagination
        $pager = $model->pager;
        $links = $pager->links('new_pagination', 'new_pagination');

        $output = array(
            'code' => 200,
            'result' => $results,
            'pager' => $pager,
            'links' => $links
        );
        echo json_encode($output);
    }

    public function getRiset()
    {
        $request = $this->request;
        $model = new \App\Models\RisetDosenModel();

        $get = $this->request->getVar('search');

        if ($get != "" && $get != NULL) {
            $results = $model->like('judul', $get)->paginate(5, 'new_pagination');
        } else {
            $results = $model->paginate(5, 'new_pagination');
        }

        // Menampilkan link pagination
        $pager = $model->pager;
        $links = $pager->links('new_pagination', 'new_pagination');

        $output = array(
            'code' => 200,
            'result' => $results,
            'pager' => $pager,
            'links' => $links
        );
        echo json_encode($output);
    }

    public function temp_login()
    {
        try {
            $model = new UserModel();
            $userModel = new \App\Models\UserModel();

            $email = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $dataemail = $model->getWhereis(['username' => $email]);
            if (!$dataemail) {
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Belum Terdaftar'
                );
                echo json_encode($res);
                exit;
            }

            $dataactive = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 0])->getRow();

            if ($dataactive) {
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Tidak Aktif'
                );
                echo json_encode($res);
                exit;
            }

            $datastatus = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 1])->getRow();

            if (!$datastatus) {
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Belum diverifikasi'
                );
                echo json_encode($res);
                exit;
            }

            if ($dataemail && $datastatus) {
                $pass = $dataemail->password;
                // $hash =  substr_replace($pass, "$2y$10", 0, 1);
                // $verify_pass = password_verify($password, $pass);
                $verify_pass = md5($password) == $pass ? 1 : 0;
                if ($verify_pass) {
                    $ses_data = [
                        'code'          => 200,
                        'msg'           => 'Login Berhasil !',
                        'username'         => $dataemail->username,
                        'id'             => $dataemail->id,
                        'role'             => $dataemail->id_role,
                        'rolename'         => $dataemail->role,
                        'logged_in'     => TRUE,
                    ];

                    echo json_encode($ses_data);
                } else {
                    $res = array(
                        'code' => 500,
                        'msg'  => 'Username atau Password salah'
                    );
                    echo json_encode($res);
                }
            } else {
                $res = array(
                    'code' => 500,
                    'msg'  => 'Username Belum Terdaftar'
                );
                echo json_encode($res);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function addLogLayanan()
    {
        $request = $this->request;

        $logModel = new \App\Models\LogModel();

        $nama = $request->getVar('nama');
        $aktifitas = $request->getVar('aktifitas');
        $keterangan = $request->getVar('keterangan');

        return $logModel->addlog([
            'tanggal' => date('Y-m-d H:i:s'),
            'nama' => $nama,
            'aktifitas' => $aktifitas,
            'keterangan' => $keterangan,
        ]);
    }

    public function login_mhs()
    {
        try {
            $model = new MahasiswaModel();

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $datauname = $model->getWhere(['nim' => $username]);
            if (!$datauname) {
                $res = array(
                    'code' => 500,
                    'msg'  => 'Data anda tidak tersedia1'
                );
                echo json_encode($res);
                exit;
            }

            $datapass = $model->getWhere(['nim' => $password])->getRow();

            if (!$datapass) {
                $res = array(
                    'code' => 500,
                    'msg'  => 'Data yang anda masukan tidak tersedia2'
                );
                echo json_encode($res);
                exit;
            }

            if ($datauname && $datapass) {
                $res = [
                    'code'          => 200,
                    'msg'           => 'Login Berhasil !',
                    'username'      => $username,
                    'logged_in'     => TRUE,
                ];
                echo json_encode($res);
            } else {
                $res = array(
                    'code' => 500,
                    'msg'  => 'Data yang anda masukan tidak tersedia3'
                );
                echo json_encode($res);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
