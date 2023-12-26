<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;
use App\Controllers\BaseController as ControllersBaseController;
use App\Models\FrontModel;
use App\Models\MhsModel;
use App\Models\UserModel;
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
            $lists = $mhsmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->npm;
                $row[] = $list->semester;
                $row[] = $list->prodi;
                $row[] = $list->status_mahasiswa == 1 ? '<span class="badge bg-success p-2"> AKTIF </span>' : '<span class="badge bg-danger p-2"> TIDAK AKTIF </span>';
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $mhsmodel->countAll(),
                'recordsFiltered' => $mhsmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }

    public function getKelas()
    {
        $request = $this->request;
        $mhsmodel = new \App\Models\KelasModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $mhsmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->no_kelas;
                $row[] = $list->matkul;
                $row[] = '<span class="badge bg-success p-2">' . $list->status . '</span>';
                $row[] = $list->jam_mulai;
                $row[] = $list->jam_akhir;
                $row[] = date('d M Y', strtotime($list->tanggal));
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $mhsmodel->countAll(),
                'recordsFiltered' => $mhsmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }
    public function getDsn()
    {
        $request = $this->request;
        $dsnmodel = new \App\Models\DosenModel();

        if ($request->getMethod(true) === 'POST') {
            $lists = $dsnmodel->getDatatables($request->getPost());
            $data = [];
            $no = $request->getPost('start');

            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->mata_kuliah;
                $row[] = $list->jadwal;
                $row[] = $list->kelas;
                $row[] = $list->perkuliahan == 'online' ? '<span class="badge bg-success p-2">Online</span>' : '<span class="badge bg-secondary p-2">Offline</span>';
                $row[] = $list->tugas;
                $data[] = $row;
            }

            $output = [
                'draw' => $request->getPost('draw'),
                'recordsTotal' => $dsnmodel->countAll(),
                'recordsFiltered' => $dsnmodel->countFiltered($request->getPost()),
                'data' => $data
            ];

            echo json_encode($output);
        }
    }

    public function temp_login()
    {
        try
			{
			$model = new UserModel();
			$userModel = new \App\Models\UserModel();

			$email = $this->request->getVar('username');
			$password = $this->request->getVar('password');
			$dataemail = $model->getWhereis(['username' => $email]);
			if(!$dataemail){
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Belum Terdaftar'
                );
                echo json_encode($res);
                exit;
			}
			
			$dataactive = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 0])->getRow();
			
			if($dataactive){
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Tidak Aktif'
                );
                echo json_encode($res);
                exit;
			}

			$datastatus = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 1])->getRow();
			
			if(!$datastatus){
                $res = array(
                    'code' => 500,
                    'msg'  => 'User Belum diverifikasi'
                );
                echo json_encode($res);
                exit;
			}
			
			if($dataemail && $datastatus){
				$pass = $dataemail->password;
				// $hash =  substr_replace($pass, "$2y$10", 0, 1);
				// $verify_pass = password_verify($password, $pass);
				$verify_pass = md5($password) == $pass ? 1 : 0;
				if($verify_pass){
					$ses_data = [			
                            'code'          => 200,			
                            'msg'           => 'Login Berhasil !',	
							'username' 		=> $dataemail->username,
							'id' 			=> $dataemail->id,
							'role' 			=> $dataemail->id_role,
							'rolename' 		=> $dataemail->role,
							'logged_in'     => TRUE,
					];
					
                    echo json_encode($ses_data);
				}else{
                    $res = array(
                        'code' => 500,
                        'msg'  => 'Username atau Password salah'
                    );
                    echo json_encode($res);
				}
			}else{
                $res = array(
                    'code' => 500,
                    'msg'  => 'Username Belum Terdaftar'
                );
                echo json_encode($res);
			}
		}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
    }
}
