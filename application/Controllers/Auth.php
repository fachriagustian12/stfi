<?php namespace App\Controllers;
use App\Models\UserModel;
use App\Models\LogModel;
use App\Controller\BaseController;

class Auth extends \CodeIgniter\Controller
{

	public function auth()
	{
		try
			{
			
			$session = session();
			$model = new UserModel();
			$userModel = new \App\Models\UserModel();
			$log = new \App\Models\LogModel();

			$email = $this->request->getVar('username');
			$password = $this->request->getVar('password');
			$dataemail = $model->getWhereis(['username' => $email]);
			if(!$dataemail){
				$session->setFlashdata('msg', 'User Belum Terdaftar');
				$data = [
					'tanggal'		=> date('Y-m-d H:i:s'),
					'nama'			=> $email,
					'aktifitas'		=> "Login",
					'keterangan'	=> "User Belum Terdaftar",
				];
				
				$log->addlog($data);
				return redirect('login');
			}
			
			$dataactive = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 0])->getRow();
			
			if($dataactive){
				$session->setFlashdata('msg', 'User Tidak Aktif');
				$data = [
					'tanggal'		=> date('Y-m-d H:i:s'),
					'nama'			=> $email,
					'aktifitas'		=> "Login",
					'keterangan'	=> "User Tidak Aktif",
				];
				
				$log->addlog($data);
				return redirect('login');
			}

			$datastatus = $model->getWhere(['m_user.username' => $email, 'm_user.status' => 1])->getRow();
			
			if(!$datastatus){
				$session->setFlashdata('msg', 'User Belum di Verifikasi');
				$data = [
					'tanggal'		=> date('Y-m-d H:i:s'),
					'nama'			=> $email,
					'aktifitas'		=> "Login",
					'keterangan'	=> "User Belum di Verifikasi",
				];
				
				$log->addlog($data);
				return redirect('login');
			}
			
			if($dataemail && $datastatus){
				$pass = $dataemail->password;
				// $hash =  substr_replace($pass, "$2y$10", 0, 1);
				// $verify_pass = password_verify($password, $pass);
				$verify_pass = md5($password) == $pass ? 1 : 0;
				if($verify_pass){
					$ses_data = [							
							'username' 		=> $dataemail->username,
							'id' 			=> $dataemail->id,
							'role' 			=> $dataemail->id_role,
							'rolename' 		=> $dataemail->role,
							'logged_in'     => TRUE,

					];
					
					$session->set($ses_data);

					$userModel->updateIsLogin($dataemail->id, ['isLogin' => 1]);
					$data = [
						'tanggal'		=> date('Y-m-d H:i:s'),
						'nama'			=> $email,
						'aktifitas'		=> "Login",
						'keterangan'	=> "Berhasil Login",
					];
					
					$log->addlog($data);
					return redirect('dashboard');
				}else{
					$session->setFlashdata('msg', 'Username atau password salah');
					$data = [
						'tanggal'		=> date('Y-m-d H:i:s'),
						'nama'			=> $email,
						'aktifitas'		=> "Login",
						'keterangan'	=> "Username atau password salah",
					];
					
					$log->addlog($data);
					return redirect('login');
				}
			}else{
					$session->setFlashdata('msg', 'User belum terdaftar');
					$data = [
						'tanggal'		=> date('Y-m-d H:i:s'),
						'nama'			=> $email,
						'aktifitas'		=> "Login",
						'keterangan'	=> "User belum terdaftar",
					];
					
					$log->addlog($data);
					return redirect('login');
			}
		}
		catch (\Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function reg()
	{
		//include helper form
		helper('form');
		//set rules validation form
		$rules = [
			'email' 		=> 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.user_email]',
			'username'		=> 'required|min_length[3]|max_length[20]',
			'password' 		=> 'required|min_length[6]|max_length[200]',
			'confpassword' 	=> 'matches[password]'
		];
		
		// if($this->validate($rules)){
			$model = new UserModel();
			$data = [
				'user_name' 	=> $this->request->getVar('register_username'),
				'user_email' 	=> $this->request->getVar('register_email'),
				'user_password' => password_hash($this->request->getVar('register_password'), PASSWORD_DEFAULT),
				'user_role' 	=> $this->request->getVar('register_tipe'),
				'user_fullname' => $this->request->getVar('register_badan_usaha')
			];
			
			$model->save($data);
			return redirect('login');
		// }else{
		// 	$data['validation'] = $this->validator;
		// 	echo view('register', $data);
		// }

	}

	public function logout()
	{
			$session = session();
			$userModel = new \App\Models\UserModel();
			$userModel->updateIsLogin($session->get('user_id'), ['isLogin' => null]);
			$session->destroy();
			return redirect('login');
	}

}
