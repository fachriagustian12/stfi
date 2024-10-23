<?php namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;
use CodeIgniter\Files\File;
use App\Models\SkripsiModel;
use App\Models\JurnalDosenModel;
use App\Models\RisetDosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use Config\App;


class Jsondata extends \CodeIgniter\Controller
{
	protected $session;
	protected $request;

  function __construct(RequestInterface $request)
  {
			$this->session = session();
			$this->now = date('Y-m-d H:i:s');
			$this->request = $request;
			$this->logged = $this->session->get('logged_in');
			$this->logModel   = new \App\Models\LogModel();
			$this->data = array(
				'version' => \CodeIgniter\CodeIgniter::CI_VERSION,
				// 'baseURL' => BASE.'/public',
				'baseURL' => BASE,
				'userid' => $this->session->get('user_id'),
				'username' => $this->session->get('username'),
				'id' => $this->session->get('id'),
				'role' => $this->session->get('role'),
				'id_provinsi' => $this->session->get('id_provinsi'),
				'provinsi' => $this->session->get('provinsi'),
				'rolename' => $this->session->get('rolename'),
				'logged_in' => $this->session->get('logged_in'),
			);
  }

  public function getalluser()
  {
	  try {
		  $request	= $this->request;
		  $param	= $request->getVar('param');
		  $user = new \App\Models\UserModel();
		  $data = $user->getUsers($param);
		  
		  if($data){
			  $response = [
				  'status'   => 'sukses',
				  'code'     => 200,
				  'data' 	 => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => 'tidak ada data',
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }
  
  public function getalllogs()
  {
	  try {
		  $request	= $this->request;
		  $param	= $request->getVar('param');
		  $log 		= new \App\Models\LogModel();
		  $data 	= $log->getlog($param);
		  
		  if($data){
			  $response = [
				  'status'   => 'sukses',
				  'code'     => 200,
				  'data' 	 => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => 'tidak ada data',
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function getuser()
  {
	  try {
		  $request	= $this->request;
		  $param 	= $request->getVar('param');
		
		  $user = new \App\Models\UserModel();
		  if($param){
		  	$data = $user->getWhere(['m_user_simponi.id_usersim' => $request->getVar('id')], $param)->getRow();
		  }else{
		  	$data = $user->getWhere(['m_user.id' => $request->getVar('id')])->getRow();
		  }
		  
		  if($data){
			  $response = [
				  'status'   => 'sukses',
				  'code'     => 200,
				  'data' 	 => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => 'tidak ada data',
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function adduser()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$user = new \App\Models\UserModel();

		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'name' 		=> $request->getVar('name'),	
						'email' 		=> $request->getVar('email'),	
						'username' 		=> $request->getVar('username'),	
						'id_role' 		=> $request->getVar('id_role'),
						'status' 		=> 1,
						'update_date' 	=> $this->now,
						'update_by' 	=> $this->session->get('id')
					];
					
					if($request->getVar('password')){
						$data['password'] = md5($request->getVar('password'));
					}
					
					$user->update($request->getVar('id'), $data);
					
				}else{
					$data = [
						'name' 			=> $request->getVar('name'),	
						'email' 		=> $request->getVar('email'),	
						'username' 		=> $request->getVar('username'),	
						'password' 		=> md5($request->getVar('password')),
						'id_role' 		=> $request->getVar('id_role'),
						'status' 		=> 1,
						'create_date' 	=> $this->now,
						'update_date' 	=> $this->now,
						'create_by' 	=> $this->session->get('id'),
						'update_by' 	=> $this->session->get('id')
					];
					$user->insert($data);
				}
		}
		redirect('user','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function deleteuser()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		$method			= $request->getMethod();
		$user = new \App\Models\UserModel();

		$user->deleteUser($request->getVar('id'));
		
		$response = [
			'status'   => 'success',
			'code'     => 200,
		];

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getlog()
  {
	  try {
		  $request	= $this->request;

		  $log = new \App\Models\LogModel();
		  $data = $log->getLogs($request->getVar('length'), $request->getVar('start'), $request->getVar('search'));
		  $count = $log->countLogs($request->getVar('search'));
		  
		  if($data){
			  $response = [
					'status'   => 'sukses',
					'code'     => 200,
					// 'draw' 		=> $request->getPost('draw'),
					'recordsTotal' => $count,
					// 'recordsFiltered' => $datatable->countFiltered(),
					'data' => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function getpenyesuaiangrafik()
  {
	  try {
		  $request	= $this->request;

		  $statistik = new \App\Models\StatistikModel();
		  $data = $statistik->getpenyesuaian();
		  
		  if($data){
			  $response = [
					'status'   => 'sukses',
					'code'     => 200,
					'data' 	   => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function addpenyesuaiangrafik()
  {
	try {
		$request		= $this->request;
		$method			= $request->getMethod();
		$statistik = new \App\Models\StatistikModel();

		if($method == 'post'){
			if($request->getVar('id_grafik')){
				$data = [
					'penyesuaian' => $request->getVar('penyesuaian'),
				];

				$statistik->update($request->getVar('id_grafik'), $data);
			}
		}
		$response = [
			'status'   => 'success',
			'code'     => 200,
		];

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getdatariwayatpnbp()
  {
	  try {
		  $request	= $this->request;

		  $statistik = new \App\Models\StatistikModel();
		  $data = $statistik->getdatariwayatpnbp();
		  
		  if($data){
			  $response = [
					'status'   => 'sukses',
					'code'     => 200,
					'data' 	   => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function getbox()
  {
	  try {
		  $request	= $this->request;
		  $box = $request->getVar('box');
		  $component = new \App\Models\ComponentModel();
		  if($box){
			$data = $component->getBoxdetail($box);
			$count = 0;
		  }else{
			$data = $component->getBox($request->getVar('length'), $request->getVar('start'), $request->getVar('search'));
			$count = count($data);
		  }
		  
		  if($data){
			  $response = [
					'status'   		=> 'sukses',
					'code'     		=> 200,
					'recordsTotal' 	=> $count,
					'data' 			=> $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function replytiket()
  {
	try {
		$request		= $this->request;
		$id		= $request->getVar('ids');
		
		$method			= $request->getMethod();
		$komunikasi = new \App\Models\KomunikasiModel();
		
		if($method == 'post'){
			if($id){
				$data = [
					'id_tiket'		=> $id,
					'isi_reply'		=> $request->getVar('reply'),
					'created_at'	=> date('Y-m-d H:i:s'),
					'created_by'	=> $this->session->get('id'),
					'updated_at'	=> date('Y-m-d H:i:s'),
					'updated_by'	=> $this->session->get('id')
				];
				
				$kom = $komunikasi->insertReply($data);
				if($kom){
					$datatiket = [
						'updated_at'	=> date('Y-m-d H:i:s'),
						'updated_by'	=> $this->session->get('id')
					];
	
					$komunikasi->updateTiket($id, $datatiket);
				}
				

			}
		}
		header("Status: 301 Moved Permanently");
		header("Location:komunikasi?detail=". $id);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function deletetiket()
  {
	try {
		$request		= $this->request;
		$param			= $request->getVar('param');
		$method			= $request->getMethod();
		$komunikasi 	= new \App\Models\KomunikasiModel();
		
		$komunikasi->deletekomunikasi($request->getVar('id'));
		
		$response = [
			'status'   => 'success',
			'code'     => 200,
		];

		header("Status: 301 Moved Permanently");
		header("Location:komunikasi");
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getcicilan()
  {
	  try {
		
		  $request		= $this->request;
		  $param		= $request->getVar('param');
		  $req = [
				'hdno'		=> $request->getVar('hdno'),
				'nama' 		=> $request->getVar('nama'),
				'alamat' 		=> $request->getVar('alamat'),
				'provinsi' 	=> $request->getVar('provinsi'),
				'kecamatan' 	=> $request->getVar('kecamatan'),
				'kabupaten' 	=> $request->getVar('kabupaten'),
				'lembaga' 	=> $request->getVar('lembaga'),
				'status' 		=> $request->getVar('status')
		  ];

		  $cicilan = new \App\Models\CicilanModel();
		  $data = $cicilan->getcicilan($request->getVar('length'), $request->getVar('start'), $request->getVar('search'), $req);
		  $count = $cicilan->countcicilan($request->getVar('search'), $req);
		  
		  if($data){
			  $response = [
					'status'   => 'sukses',
					'code'     => 200,
					// 'draw' 		=> $request->getPost('draw'),
					'recordsTotal' => $count->jumlah,
					// 'recordsFiltered' => $datatable->countFiltered(),
					'data' => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function getwilayah()
  {
	  try {
		  $request	= $this->request;
		  $param	= $request->getVar('param');
		  $id		= $request->getVar('id');
		  $user = new \App\Models\UserModel();
		  if($param == 'kabupaten'){
		  	$data = $user->getkabupaten($id);
		  }else if($param == 'kecamatan'){
			$data = $user->getkecamatan($id);
		  }
		  
		  if($data){
			  $response = [
				  'status'   => 'sukses',
				  'code'     => 200,
				  'data' 	 => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => [],
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function ubahpembayaran()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$cicilan = new \App\Models\CicilanModel();

		if($method == 'post'){
			$data = [
				'kode_billing' => $request->getVar('kode_billing'),
				'ntpn' => $request->getVar('ntpn'),
				'ntbp' => $request->getVar('ntbp'),
				'setoran' => $request->getVar('setoran'),
				'kode_akun' => $request->getVar('kode_akun'),
				'keterangan' => $request->getVar('keterangan'),
				'angsuran_ke' => $request->getVar('angsuran_ke'),
				'status' => 1
			];
			
			$cicilan->ubahpembayaran($request->getVar('id_rumah'), $request->getVar('id'), $data);
			$this->logModel->insert([
				'tgl' => date('Y-m-d H:i:s'), 
				'username' => $this->session->get('username'), 
				'keterangan' => "Mengubah data pembayaran ".$request->getVar('hdno'),
			]);
		}
		$response = [
					'status'   => 'success',
					'code'     => '200',
				];

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function pembayaran()
  {
	try {
		$request		= $this->request;
		
		$method			= $request->getMethod();
		$cicilan = new \App\Models\CicilanModel();

		if($method == 'post'){
			$data = [
				'id_rumah' => $request->getVar('id_rumah'),
				'kode_billing' => $request->getVar('kode_billing'),
				'ntpn' => $request->getVar('ntpn'),
				'ntbp' => $request->getVar('ntbp'),
				'setoran' => $request->getVar('setoran'),
				'kode_akun' => $request->getVar('kode_akun'),
				'keterangan' => $request->getVar('keterangan'),
				'angsuran_ke' => $request->getVar('angsuran_ke'),
				'tgl_bayar' => $this->now,
				'status' => $request->getVar('status')
			];
			
			$cicilan->pembayaran($data);
			$this->logModel->insert([
				'tgl' => date('Y-m-d H:i:s'), 
				'username' => $this->session->get('username'), 
				'keterangan' => "Menambahkan data pembayaran ".$request->getVar('hdno'),
			]);
		}
		$response = [
					'status'   => 'success',
					'code'     => '200',
				];

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function kodebilling()
  {
	try {
		$request		= $this->request;
		
		$method			= $request->getMethod();
		$billing = new \App\Models\BillingModel();

		if($method == 'post'){
			$data = [
						'id_rumah' => $request->getVar('id_rumah'),
						'expired_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + '.$request->getVar('batas').' days')),
						'kode_kl' => '033',
						'kode_eselon_1' => '05',
						'kode_satker' => '452780',
						'jenis_pnbp' => 'U',
						'kode_mata_uang' => '1',
						'total_nominal_billing' => $request->getVar('total'),
						'nama_wajib_bayar' => $request->getVar('nama'),
						'created_at' => $this->now,
						'created_by' => $this->session->get('id')
					];
			
			$lastid = $billing->save_billing($request->getVar('id_rumah'), $data);
			
			foreach ($request->getVar('detail') as $key => $value) {
				//save billing
				$data_bil = [
					'id_billing' => $lastid,
					'nama_wajib_bayar' => $value['namabayar'],
					'kode_tarif_simponi' => $value['akunbayar'],
					'kode_pp_simponi' => 'YYYYYYY',
					'kode_akun' => $value['akunbayar'],
					'nominal_tarif_pnbp' => $value['tarifbayar'],
					'volume' => $value['volume'],
					'satuan_tarif' => '-',
					'total_tarif_per_record' => $value['totaltarifbayar'],
					'keterangan' => $value['keteranganbayar']
				];

				//insert ke database
				$billing->save_billing_detail($data_bil);
			}
			$this->logModel->insert([
				'tgl' => date('Y-m-d H:i:s'), 
				'username' => $this->session->get('username'), 
				'keterangan' => "Membuat billing SIMPONI",
			]);
		}
		$response = [
					'status'   => 'success',
					'code'     => '200',
				];

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addslider()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$image = new \App\Models\ImageModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'title' 		=> $request->getVar('title'),	
						'status' 		=> 1,
						'update_date' 	=> $this->now,
						'update_by' 	=> $this->session->get('id'),
						'type' 			=> 'slider'
					];
					
					$image->updateImage($request->getVar('id'), $data);
					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							
							$basepath = './uploads/slider/'.$request->getVar('id').'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							if($tmp_name){
								$files = glob("$basepath*"); // get all file names
								foreach($files as $file){ // iterate files
									if(is_file($file)) {
										unlink($file); // delete file
									}
								}
								$path = $value['name'][0];
								$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
								$imgname = "slider-".$request->getVar('id')."-".$path;
								$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
								$image->updateImage($request->getVar('id'), ['path' => $basepath.$imgname]);
							}
						}

					};
					
				}else{
					$data = [
						'title' 		=> $request->getVar('title'),	
						'status' 		=> 1,
						'create_date' 	=> $this->now,
						'update_date' 	=> $this->now,
						'create_by' 	=> $this->session->get('id'),
						'update_by' 	=> $this->session->get('id'),
						'type' 			=> 'slider'
					];
					$image->insertImage($data);
					$lastid = $image->insertID();

					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							$basepath = './uploads/slider/'.$lastid.'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							$path = $value['name'][0];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							$imgname = "slider-".$lastid."-".$path;
							$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
							$image->updateImage($lastid, ['path' => $basepath.$imgname]);
						}

					};
				}
		}
		redirect('data_slider','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addmahasiswa()
  {
	try {
		$request = $this->request;
		$param = $request->getVar('param');
		
		$method	= $request->getMethod();
		$mahasiswa = new MahasiswaModel();
		$user = new UserModel();

		// $idRole = $request->getVar('id_role');
		$idRole = 2;

		if($method == 'post'){
			$part1 = bin2hex(random_bytes(4));
			$part2 = bin2hex(random_bytes(2));
			$part3 = bin2hex(random_bytes(2));
			$part4 = bin2hex(random_bytes(2));
			$part5 = bin2hex(random_bytes(6));
			$uuid = sprintf('%s-%s-%s-%s-%s', $part1, $part2, $part3, $part4, $part5);
			if($request->getVar('id')){
				$data = [
					'nama' => $request->getVar('nama'),
					'nm_mhs' => $request->getVar('nama'),
					'npm' => $request->getVar('npm'),
					'nim' => $request->getVar('npm'),
					'id_angkatan' => $request->getVar('angkatan'),
					'prodi' => $request->getVar('prodi'),
					'nm_prodi' => $request->getVar('prodi'),
					'semester' => $request->getVar('semester'),
					'status_mahasiswa' => $request->getVar('status_mahasiswa'),
					'status_mhs' => $request->getVar('status_mahasiswa'),
					'status_perwalian' => $request->getVar('status_perwalian'),
					'update_date' => $this->now,
					'update_by' => $this->session->get('id')
				];
				$mahasiswa->update($request->getVar('id'), $data);

				$userid = $request->getVar('userid');
				if($userid){
					$datas = [
						'name' => $request->getVar('nama'),	
						'email' => $request->getVar('email'),	
						'username' => $request->getVar('npm'),
						'update_date' => $this->now,
						'update_by' => $this->session->get('id')
					];
					
					if($request->getVar('password')){
						$datas['password'] = md5($request->getVar('password'));
					}
					$user->updateUser($request->getVar('userid'), $datas);
				}else{
					$datas = [
						'name' => $request->getVar('nama'),	
						'email' => $request->getVar('email'),	
						'username' => $request->getVar('npm'),	
						'password' => md5($request->getVar('password')),
						'id_role' => $idRole,
						'status' => 1,
						'create_date' => $this->now,
						'create_by' => $this->session->get('id')
					];
					$user->insert($datas);
				}
			}else{
				$data = [
					'id' => $uuid,
					'id_mhs' => $uuid,
					'nama' => $request->getVar('nama'),
					'nm_mhs' => $request->getVar('nama'),
					'npm' => $request->getVar('npm'),
					'nim' => $request->getVar('npm'),
					'id_angkatan' => $request->getVar('angkatan'),
					'prodi' => $request->getVar('prodi'),
					'nm_prodi' => $request->getVar('prodi'),
					'semester' => $request->getVar('semester'),
					'status_mahasiswa' => $request->getVar('status_mahasiswa'),
					'status_mhs' => $request->getVar('status_mahasiswa'),
					'status_perwalian' => $request->getVar('status_perwalian'),
					'create_date' => $this->now,
					'create_by' => $this->session->get('id')
				];
				$mahasiswa->insertMhs($data);
				$lastid = $mahasiswa->insertID();
				$datas = [
					'name' => $request->getVar('nama'),	
					'email' => $request->getVar('email'),	
					'username' => $request->getVar('npm'),	
					'password' => md5($request->getVar('password')),
					'id_role' => $idRole,
					'status' => 1,
					'create_date' => $this->now,
					'create_by' => $this->session->get('id')
				];
				$user->insert($datas);
			}
		}
		redirect('data_mahasiswa','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function adddosen()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$dosen = new \App\Models\DosenModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'nama' 			=> $request->getVar('nama'),
						'nm_dosen' 		=> $request->getVar('nama'),
						'nm_jab' 		=> $request->getVar('nm_jab'),
						'nm_pangkat' 	=> $request->getVar('nm_pangkat'),
						'kategori_dosen'=> $request->getVar('kategori_dosen'),
						'alamat' 		=> $request->getVar('alamat'),
						'kontak' 		=> $request->getVar('kontak'),
						'mata_kuliah' 	=> $request->getVar('mata_kuliah'),
						'jadwal' 		=> $request->getVar('jadwal'),
						'kelas' 		=> $request->getVar('kelas'),
						'perkuliahan' 	=> ($request->getVar('perkuliahan') == 'on') ? 'online' : 'offline',
						'status' 		=> 1,
						'tugas' 		=> $request->getVar('tugas'),
						'update_by' 	=> $this->session->get('id'),
						'update_date' 	=> $this->now
					];
					
					$dosen->update($request->getVar('id'), $data);

					
				}else{
					$data = [
						'nama' 			=> $request->getVar('nama'),
						'nm_dosen' 		=> $request->getVar('nama'),
						'nm_jab' 		=> $request->getVar('nm_jab'),
						'nm_pangkat' 	=> $request->getVar('nm_pangkat'),
						'kategori_dosen'=> $request->getVar('kategori_dosen'),
						'alamat' 		=> $request->getVar('alamat'),
						'kontak' 		=> $request->getVar('kontak'),
						'mata_kuliah' 	=> $request->getVar('mata_kuliah'),
						'jadwal' 		=> $request->getVar('jadwal'),
						'kelas' 		=> $request->getVar('kelas'),
						'perkuliahan' 	=> ($request->getVar('perkuliahan') == 'on') ? 'online' : 'offline',
						'status' 		=> 1,
						'tugas' 		=> $request->getVar('tugas'),
						'create_by' 	=> $this->session->get('id'),
						'create_date' 	=> $this->now,
						'update_by' 	=> $this->session->get('id'),
						'update_date' 	=> $this->now
					];
					
					
					$dosen->insert($data);
					$lastid = $dosen->insertID();

				}
		}
		redirect('data_dosen','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addkegiatan()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$kegiatan = new \App\Models\KegiatanModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'kegiatan' => $request->getVar('kegiatan'),
						'tanggal_kegiatan' => $request->getVar('tanggal_kegiatan'),
						'keterangan' => $request->getVar('keterangan'),
						'update_date' => $this->now,
						'update_by' => $this->session->get('id'),
						'status' => 1

					];
					
					$kegiatan->update($request->getVar('id'), $data);
					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							
							$basepath = './uploads/kegiatan/'.$request->getVar('id').'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							if($tmp_name){
								$files = glob("$basepath*"); // get all file names
								foreach($files as $file){ // iterate files
									if(is_file($file)) {
										unlink($file); // delete file
									}
								}
								$path = $value['name'][0];
								$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
								$imgname = "kegiatan-".$request->getVar('id')."-".$path;
								$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
								$kegiatan->update($request->getVar('id'), ['path' => $basepath.$imgname]);
							}
						}

					};
					
				}else{

					$data = [
						'kegiatan' => $request->getVar('kegiatan'),
						'tanggal_kegiatan' => $request->getVar('tanggal_kegiatan'),
						'keterangan' => $request->getVar('keterangan'),
						'create_date' 	=> $this->now,
						'update_date' => $this->now,
						'create_by' 	=> $this->session->get('id'),
						'update_by' => $this->session->get('id'),
						'status' => 1
					];
					$kegiatan->insert($data);
					$lastid = $kegiatan->insertID();
					
					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							$basepath = './uploads/kegiatan/'.$lastid.'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							$path = $value['name'][0];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							$imgname = "kegiatan-".$lastid."-".$path;
							$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
							$kegiatan->update($lastid, ['path' => $basepath.$imgname]);
						}

					};
				}
		}
		redirect('data_kampus','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addberita()
  {
	try {
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$berita = new \App\Models\BeritaModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'title' => $request->getVar('title'),
						'redaksi' => $request->getVar('redaksi'),
						'tanggal' => $request->getVar('tanggal'),
						'kategori' => $request->getVar('kategori'),
						'update_date' => $this->now,
						'update_by' => $this->session->get('id'),
						'status' => 1

					];
					
					$berita->update($request->getVar('id'), $data);
					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							
							$basepath = './uploads/berita/'.$request->getVar('id').'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							if($tmp_name){
								$files = glob("$basepath*"); // get all file names
								foreach($files as $file){ // iterate files
									if(is_file($file)) {
										unlink($file); // delete file
									}
								}
								$path = $value['name'][0];
								$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
								$imgname = "berita-".$request->getVar('id')."-".$path;
								$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
								$berita->update($request->getVar('id'), ['path' => $basepath.$imgname]);
							}
						}

					};
					
				}else{

					$data = [
						'title' => $request->getVar('title'),
						'redaksi' => $request->getVar('redaksi'),
						'tanggal' => $request->getVar('tanggal'),
						'kategori' => $request->getVar('kategori'),
						'create_date' 	=> $this->now,
						'update_date' => $this->now,
						'create_by' 	=> $this->session->get('id'),
						'update_by' => $this->session->get('id'),
						'status' => 1
					];
					$berita->insert($data);
					$lastid = $berita->insertID();
					
					if(array_key_exists("images",$_FILES)){

						foreach ($_FILES as $key => $value) {
							$basepath = './uploads/berita/'.$lastid.'/';
							if(!is_dir($basepath)){
								mkdir($basepath, 0777, true);
							}
							
							$tmp_name = $value['tmp_name'][0];
							$path = $value['name'][0];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							$imgname = "berita-".$lastid."-".$path;
							$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
							$berita->update($lastid, ['path' => $basepath.$imgname]);
						}

					};
				}
		}
		redirect('data_berita','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getBerita(){
	try {
		$table = 'berita';
		$user = new \App\Models\UserModel();
		$data = $user->getData($table);
        $response = [
			'status'	=> 'sukses',
			'code'		=> 200,
			'data'		=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getDetailBerita($id = null){
	try {
		$table	= 'berita';
		$user 	= new \App\Models\UserModel();
		$data 	= $user->getData($table, $id);

        $response = [
			'status'   	=> 'sukses',
			'code'     	=> 200,
			'data' 	 	=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addkelas()
  {
	try {
		$request	= $this->request;
		$param		= $request->getVar('param');
		
		$method		= $request->getMethod();
		$kelas 		= new \App\Models\KelasModel();
		
		$tanggal = $request->getVar('tanggal');
		$tanggal .= ' 00:00:00.000000';
		
		$reqdosen = $request->getVar('nm_dosen');
		$dosenmodel = new \App\Models\UserModel();
		$paydosen = $dosenmodel->getDosen($reqdosen);
		$kd_dosen = $paydosen->kd_dosen;

		if($method == 'post'){
			if($request->getVar('id')){

				$data = [
					'nama'			=> $request->getVar('nama'),
					'no_kelas'		=> $request->getVar('no_kelas'),
					'matkul'		=> $request->getVar('matkul'),
					'jam_mulai'		=> $request->getVar('jam_mulai'),
					'jam_akhir'		=> $request->getVar('jam_akhir'),
					'updated_at'	=> $this->now,
					'updated_by'    => $this->session->get('id'),
					'nm_hari'		=> $request->getVar('hari'),
					'kelas'			=> $request->getVar('kelas'),
					'kd_dosen'		=> $kd_dosen,
				];
				$kelas->update($request->getVar('id'), $data);
			}else{
				$data = [
					'nama'			=> $request->getVar('nama'),
					'no_kelas'		=> $request->getVar('no_kelas'),
					'matkul'		=> $request->getVar('matkul'),
					'jam_mulai'		=> $request->getVar('jam_mulai'),
					'jam_akhir'		=> $request->getVar('jam_akhir'),
					'created_at'	=> $this->now,
					'created_by'	=> $this->session->get('id'),
					'nm_hari'		=> $request->getVar('hari'),
					'kelas'			=> $request->getVar('kelas'),
					'kd_dosen'		=> $kd_dosen,
				];
				$kelas->insert($data);
				$lastid = $kelas->insertID();
			}
		}
		redirect('data_kelas','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getPerkuliahan(){
	try {
		$table = 'kelas';
		$user = new \App\Models\UserModel();
		$data = $user->getData($table);
        $response = [
			'status'	=> 'sukses',
			'code'		=> 200,
			'data'		=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getDetailPerkuliahan($id = null){
	try {
		$table	= 'kelas';
		$user 	= new \App\Models\UserModel();
		$data 	= $user->getData($table, $id);

        $response = [
			'status'   	=> 'sukses',
			'code'     	=> 200,
			'data' 	 	=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addjadwalpraktikum()
  {
	try {
		$request	= $this->request;
		$param		= $request->getVar('param');
		
		$method		= $request->getMethod();
		$praktik 	= new \App\Models\PraktikumModel();
		
		$tanggal 	= $request->getVar('tanggal');
		$tanggal 	.= ' 00:00:00.000000';

		$reqdosen = $request->getVar('nm_dosen');
		$dosenmodel = new \App\Models\UserModel();
		$paydosen = $dosenmodel->getDosen($reqdosen);
		$kd_dosen = $paydosen->kd_dosen;

		if($method == 'post'){
			if($request->getVar('id')){
				
					$data = [
						'ruangan_praktikum'			=> $request->getVar('ruangan_praktikum'),
						'mata_kuliah_praktikum'		=> $request->getVar('mata_kuliah_praktikum'),
						'nip_dosen'					=> $kd_dosen,
						'nama_kelompok'				=> $request->getVar('nama_kelompok'),
						'nama_hari'					=> $request->getVar('nama_hari'),
						'jam_mulai'					=> $request->getVar('jam_mulai'),
						'jam_akhir'					=> $request->getVar('jam_akhir'),
						'updated_at'				=> $this->now,
						'updated_by'				=> $this->session->get('id'),
					];
					$praktik->update($request->getVar('id'), $data);
				}else{
					$data = [
						'ruangan_praktikum'			=> $request->getVar('ruangan_praktikum'),
						'mata_kuliah_praktikum'		=> $request->getVar('mata_kuliah_praktikum'),
						'nip_dosen'					=> $kd_dosen,
						'nama_kelompok'				=> $request->getVar('nama_kelompok'),
						'nama_hari'					=> $request->getVar('nama_hari'),
						'jam_mulai'					=> $request->getVar('jam_mulai'),
						'jam_akhir'					=> $request->getVar('jam_akhir'),
						'created_at'				=> $this->now,
						'created_by'				=> $this->session->get('id'),
					];
					$praktik->insert($data);
					$lastid = $praktik->insertID();
				}
		}
		redirect('data_jadwal_praktikum','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getPraktikum(){
	try {
		$request = $this->request;
		$user = new \App\Models\UserModel();
		if($request->getVar('id')){
			$data = $user->getDosenPraktik($request->getVar('id'));
		}else{
			$data = $user->getDosenPraktik();
		}
        $response = [
			'status'	=> 'sukses',
			'code'		=> 200,
			'data'		=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getDetailPraktikum($id = null){
	try {
		$table	= 'jadwal_praktikum';
		$user 	= new \App\Models\UserModel();
		$data 	= $user->getDosenPraktik($table, $id);

        $response = [
			'status'   	=> 'sukses',
			'code'     	=> 200,
			'data' 	 	=> $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addbukus(){
	try {
		$request = $this->request;
		$buku = new \App\Models\BukuModel();
		$config = new App();
        $baseUrl = $config->baseURL;
		$filePath = null;
		if ($file = $request->getFile('path')) {
            if ($file->isValid() && !$file->hasMoved()) {
                if (!is_dir('storage/buku')) {
                    mkdir('storage/buku', 0777, true);
                }
                $newName = $file->getRandomName();
                $file->move('storage/buku', $newName);
                $filePath = 'storage/buku/' . $newName;
            }
        }
		if ($request->getVar('id')) {
			$existingBuku = $buku->find($request->getVar('id'));
            $data = [
                'title' => $request->getVar('title'),
                'pengarang' => $request->getVar('pengarang'),
                'penerbit' => $request->getVar('penerbit'),
                'tempat_terbit' => $request->getVar('tempat_terbit'),
                'tahun_terbit' => $request->getVar('tahun_terbit'),
                'path' => $filePath ?? $existingBuku['path'],
                'url_file' => ($filePath ? $baseUrl . $filePath : $baseUrl . $existingBuku['path']),
                'updated_at' => $this->now,
                'updated_by' => $this->session->get('id'),
            ];
            $buku->update($request->getVar('id'), $data);
        } else {
            $data = [
                'title' => $request->getVar('title'),
                'pengarang' => $request->getVar('pengarang'),
                'penerbit' => $request->getVar('penerbit'),
                'tempat_terbit' => $request->getVar('tempat_terbit'),
                'tahun_terbit' => $request->getVar('tahun_terbit'),
                'path' => $filePath,
                'url_file' => $filePath ? $baseUrl . $filePath : null,
                'created_at' => $this->now,
                'created_by' => $this->session->get('id'),
            ];
            $buku->insert($data);
            $lastid = $buku->insertID();
        }
		// redirect('data_buku','refresh');
		$output = [
            'code' => 200,
            'data' => 'success'
        ];

        echo json_encode($output);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }
  
  public function addskripsis(){
	try {
		$request = $this->request;
		$buku = new SkripsiModel();
		if($request->getVar('id_skripsi')){
			$data = [
				'judul_buku' => $request->getVar('judul_buku_skripsi'),
				'pengarang' => $request->getVar('pengarang_skripsi'),
				'penerbit' => $request->getVar('penerbit_skripsi'),
				'tempat_terbit' => $request->getVar('tempat_terbit_skripsi'),
				'tahun_terbit' => $request->getVar('tahun_terbit_skripsi'),
				'kondisi_buku' => $request->getVar('kondisi_buku_skripsi'),
				'updated_at' => $this->now,
				'updated_by' => $this->session->get('id'),
			];
			$buku->update($request->getVar('id_skripsi'), $data);
		}else{
			$data = [
				'judul_buku' => $request->getVar('judul_buku_skripsi'),
				'pengarang' => $request->getVar('pengarang_skripsi'),
				'penerbit' => $request->getVar('penerbit_skripsi'),
				'tempat_terbit' => $request->getVar('tempat_terbit_skripsi'),
				'tahun_terbit' => $request->getVar('tahun_terbit_skripsi'),
				'kondisi_buku' => $request->getVar('kondisi_buku_skripsi'),
				'created_at' => $this->now,
				'created_by' => $this->session->get('id'),
			];
			$buku->insert($data);
			$lastid = $buku->insertID();
		}
		redirect('data_buku','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }
  
  public function addjurnals(){
	try {
		$request = $this->request;
		$skripsi = new JurnalDosenModel();
		if($request->getVar('id_jurnal')){
			$data = [
				'nidn' => $request->getVar('nidn_jurnal'),
				'kode_dosen' => $request->getVar('nm_dosen_jurnal'),
				'judul' => $request->getVar('judul_jurnal'),
				'jenis_jurnal' => $request->getVar('jenis_jurnal'),
				'tahun' => $request->getVar('tahun_jurnal'),
				'updated_at' => $this->now,
			];
			$skripsi->update($request->getVar('id_jurnal'), $data);
		}else{
			$data = [
				'nidn' => $request->getVar('nidn_jurnal'),
				'kode_dosen' => $request->getVar('nm_dosen_jurnal'),
				'judul' => $request->getVar('judul_jurnal'),
				'jenis_jurnal' => $request->getVar('jenis_jurnal'),
				'tahun' => $request->getVar('tahun_jurnal'),
				'created_at' => $this->now,
			];
			$skripsi->insert($data);
			$lastid = $skripsi->insertID();
		}
		redirect('data_buku','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }
  
  public function addrisets(){
	try {
		$request = $this->request;
		$riset = new RisetDosenModel();
		if($request->getVar('id_riset')){
			$data = [
				'nidn' => $request->getVar('nidn_riset'),
				'kode_dosen' => $request->getVar('nm_dosen_riset'),
				'judul' => $request->getVar('judul_riset'),
				'jenis_karyailmiah' => $request->getVar('jenis_riset'),
				'tahun' => $request->getVar('tahun_riset'),
				'updated_at' => $this->now,
			];
			$riset->update($request->getVar('id_riset'), $data);
		}else{
			$data = [
				'nidn' => $request->getVar('nidn_riset'),
				'kode_dosen' => $request->getVar('nm_dosen_riset'),
				'judul' => $request->getVar('judul_riset'),
				'jenis_karyailmiah' => $request->getVar('jenis_riset'),
				'tahun' => $request->getVar('tahun_riset'),
				'created_at' => $this->now,
			];
			$riset->insert($data);
			$lastid = $riset->insertID();
		}
		redirect('data_buku','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function addbuku()
  {
	try {
		$request		= $this->request;
		$param			= $request->getVar('param');
		$method			= $request->getMethod();
		$tanggalString 	= $this->now;
        $tanggal_saja 	= date("Y-m-d", strtotime($tanggalString));

		$image = $request->getFile('cover');
		$pdf = $request->getFile('pdf');

		
		$buku = new \App\Models\BukuModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'title' => $request->getVar('title'),
						'keterangan' => $request->getVar('keterangan'),
						'ketersediaan' => $request->getVar('ketersediaan'),
						'tanggal' => $tanggal_saja,
						'updated_at' => $this->now,
						'updated_by' => $this->session->get('id'),
						'status' => 1
					];
					
					$buku->update($request->getVar('id'), $data);

					if (!empty($image->getTempName()) && file_exists($image->getTempName())) {
						$path = $image->getTempName();
						$fi = finfo_open(FILEINFO_MIME_TYPE);
						$originalMimeType = finfo_file($fi, $path);
						finfo_close($fi);
						
						$originalName = $image->getName();
						$gambarFileName	= './uploads/cover/cover-'.$request->getVar('id').'-'.$originalName;
						
						$basepath = './uploads/cover/'.$request->getVar('id').'/';
						if(!is_dir($basepath)){
							mkdir($basepath, 0777, true);
						}
						$gambarFileName	= $basepath.'cover-'.$request->getVar('id').'-'.$originalName;
						$terupload = move_uploaded_file($path,$gambarFileName);
						$buku->update($request->getVar('id'), ['cover' => $gambarFileName]);
					} else {
						$cover_hidden = $request->getVar('cover_hidden');
						$buku->update($request->getVar('id'), ['cover' => $cover_hidden]);
					};

					if (!empty($pdf->getTempName()) && file_exists($pdf->getTempName())) {
						$path = $pdf->getTempName();
						$fi = finfo_open(FILEINFO_MIME_TYPE);
						$originalMimeType = finfo_file($fi, $path);
						finfo_close($fi);
						
						$originalName = $pdf->getName();
						$pdfFileName	= './uploads/buku/buku-'.$request->getVar('id').'-'.$originalName;
						
						$basepath = './uploads/buku/'.$request->getVar('id').'/';
						if(!is_dir($basepath)){
							mkdir($basepath, 0777, true);
						}
						$pdfFileName	= $basepath.'buku-'.$request->getVar('id').'-'.$originalName;
						$terupload = move_uploaded_file($path,$pdfFileName);
						$buku->update($request->getVar('id'), ['path' => $pdfFileName]);
					} else {
						$path_hidden = $request->getVar('path_hidden');
						$buku->update($request->getVar('id'), ['path' => $path_hidden]);
					};
					// if(array_key_exists("images",$_FILES)){
					// 	foreach ($_FILES as $key => $value) {
					// 		if (!empty($value['tmp_name'])) {
					// 			var_dump($value);die;
					// 			$basepath = './uploads/buku/'.$request->getVar('id').'/';
					// 			if(!is_dir($basepath)){
					// 				mkdir($basepath, 0777, true);
					// 			}
					// 			$tmp_name = $value['tmp_name'][0];
					// 			$path = $value['name'][0];
					// 			$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					// 			$imgname = "buku-".$request->getVar('id')."-".$path;
					// 			$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
					// 			$buku->update($request->getVar('id'), ['path' => $basepath.$imgname]);
					// 		}else{
					// 			var_dump($pathe);die;
					// 			$buku->update($request->getVar('id'), ['path' => $pathe]);
					// 		}
					// 	}
					// };
					
				}else{

					$data = [
						'title' 		=> $request->getVar('title'),
						'keterangan' 	=> $request->getVar('keterangan'),
						'ketersediaan' 	=> $request->getVar('ketersediaan'),
						'tanggal'		=> $tanggal_saja,
						'created_at' 	=> $this->now,
						'created_by' 	=> $this->session->get('id'),
						'status' 		=> 1
					];
					$buku->insert($data);
					$lastid = $buku->insertID();

					$path = $image->getTempName();
					$fi = finfo_open(FILEINFO_MIME_TYPE);
					$originalMimeType = finfo_file($fi, $path);
					finfo_close($fi);

					$originalName = $image->getName();
					$gambarFileName	= './uploads/cover/cover-'.$lastid.'-'.$originalName;

					$basepath = './uploads/cover/'.$lastid.'/';
					if(!is_dir($basepath)){
						mkdir($basepath, 0777, true);
					}
					$gambarFileName	= $basepath.'cover-'.$lastid.'-'.$originalName;
					$terupload = move_uploaded_file($path, $gambarFileName);
					$buku->update($lastid, ['cover' => $gambarFileName]);


					
					$pathpdf = $pdf->getTempName();
					$fipdf = finfo_open(FILEINFO_MIME_TYPE);
					$originalMimeTypepdf = finfo_file($fipdf, $pathpdf);
					finfo_close($fipdf);
					
					$originalNamepdf = $pdf->getName();
					$pdfFileName	= './uploads/buku/buku-'.$lastid.'-'.$originalNamepdf;
					
					$basepathpdf = './uploads/buku/'.$lastid.'/';
					if(!is_dir($basepathpdf)){
						mkdir($basepathpdf, 0777, true);
					}
					$pdfFileName	= $basepathpdf.'buku-'.$lastid.'-'.$originalNamepdf;
					$teruploadpdf = move_uploaded_file($pathpdf,$pdfFileName);
					$buku->update($lastid, ['path' => $pdfFileName]);
				
					// if(array_key_exists("images",$_FILES)){
					// 	foreach ($_FILES as $key => $value) {
					// 		$basepath = './uploads/buku/'.$lastid.'/';
					// 		if(!is_dir($basepath)){
					// 			mkdir($basepath, 0777, true);
					// 		}
					// 		$tmp_name = $value['tmp_name'][0];
					// 		$path = $value['name'][0];
					// 		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					// 		$imgname = "buku-".$lastid."-".$path;
					// 		$terupload = move_uploaded_file($tmp_name, $basepath.$imgname);
					// 		$buku->update($lastid, ['path' => $basepath.$imgname]);
					// 	}

					// };
				}
		}
		redirect('data_buku','refresh');
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getBuku(){
	try {
		$table = 'buku';
		$user = new \App\Models\UserModel();
		$data = $user->getData($table);
        $response = [
			'status'   => 'sukses',
			'code'     => 200,
			'data' 	 => $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getDetailBuku($id = null){
	try {
		$table	= 'buku';
		$user 	= new \App\Models\UserModel();
		$data 	= $user->getData($table, $id);

        $response = [
			'status'   => 'sukses',
			'code'     => 200,
			'data' 	 => $data
		];
		header('Content-Type: application/json');
	  echo json_encode($response);
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getdata()
  {
	  try {
		  $request	= $this->request;
		  $table = $request->getVar('table');
		  $id = $request->getVar('id');
		  $user = new \App\Models\UserModel();
		  
		  if($table == 'perkuliahan'){
			$data = $user->joinDosen();
		  }else if($table == 'jurnal_dosen'){
			if($id){
				$data = $user->jurnaldosen($id);
			}else{
				$data = $user->jurnaldosen();
			}
		  }else if($table == 'riset_dosen'){
			if($id){
				$data = $user->risetdosen($id);
			}else{
				$data = $user->risetdosen();
			}
		  }else if($table == 'mahasiswa'){
			if($id){
				$data = $user->getUserMahasiswa($id);
			}else{
				$data = $user->getUserMahasiswa();
			}
		  }else{
			$data = $user->getData($table, $id);
		  }
		  
		  if($data){
			  $response = [
				  'status'   => 'sukses',
				  'code'     => 200,
				  'data' 	 => $data
			  ];
		  }else{
			  $response = [
				  'status'   => 'gagal',
				  'code'     => '0',
				  'data'     => 'tidak ada data',
			  ];
		  }

	  header('Content-Type: application/json');
	  echo json_encode($response);
	  exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function getdataid(){
	try {
		$request = $this->request;
		$table = $request->getVar('table');
		$id = $request->getVar('id');
		$data_perkuliahan = new \App\Models\UserModel();
		$data = $data_perkuliahan->getData($table, $id);

		$idDosen = $data->kd_dosen;
		$datadosen = $data_perkuliahan->getDosen($idDosen);

		$datas = [
			'data' => $data,
			'DataDosen' => $datadosen
		];

		if($datas){
			$response = [
				'status'   => 'sukses',
				'code'     => 200,
				'data' 	 => $datas
			];
		}else{
			$response = [
				'status'   => 'gagal',
				'code'     => '0',
				'data'     => 'tidak ada data',
			];
		}

		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getlistdosen(){
		$Dosen 		= new \App\Models\DosenModel();
		$GetData	= $Dosen->getAllData();

		if($GetData){
			$response = [
				'status'   => 'sukses',
				'code'     => 200,
				'data' 	 => $GetData
			];
		}else{
			$response = [
				'status'   => 'gagal',
				'code'     => '0',
				'data'     => 'tidak ada data',
			];
		}
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
  }

//   public function getdatadosen()
//   {
// 	  try {
// 			$ajarDosen 		= new \App\Models\AjarDosenModel();
// 		  	$table          = "ajar_dosen";
// 			$field          = "kd_dosen, nm_dosen, nm_mk";
// 			$jadwall        = [];
// 			$jadwal_dosen   = $ajarDosen->getJadwalDosen($field, $table);
// 			foreach ($jadwal_dosen as $key => $value) {
// 				$jadwal_praktikum = $ajarDosen->getJadwalDosen("jam_mulai, jam_akhir, nama_hari, nm_kelas, ruangan_praktikum", "jadwal_praktikum", "nip_dosen", $value->kd_dosen);
// 				$value->jadwal = $jadwal_praktikum;
//                 $jadwall[] = $value;
// 			}

// 			if($jadwall){
// 				$response = [
// 					'status'   => 'sukses',
// 					'code'     => 200,
// 					'data' 	 => $jadwall
// 				];
// 			}else{
// 				$response = [
// 					'status'   => 'gagal',
// 					'code'     => '0',
// 					'data'     => 'tidak ada data',
// 				];
// 			}
// 			header('Content-Type: application/json');
// 			echo json_encode($response);
// 			exit;
// 	  } catch (\Exception $e) {
// 		  die($e->getMessage());
// 	  }
//   }

  public function getdatadosen()
  {
	  try {
			$ajarDosen 		= new \App\Models\AjarDosenModel();
		  	$table          = "dosen";
			$field          = "*";
			$jadwall        = [];
			$jadwal_dosen   = $ajarDosen->getJadwalDosen($field, $table);

			if($jadwal_dosen){
				$response = [
					'status'   => 'sukses',
					'code'     => 200,
					'data' 	 => $jadwal_dosen
				];
			}else{
				$response = [
					'status'   => 'gagal',
					'code'     => '0',
					'data'     => 'tidak ada data',
				];
			}
			header('Content-Type: application/json');
			echo json_encode($response);
			exit;
	  } catch (\Exception $e) {
		  die($e->getMessage());
	  }
  }

  public function deletedata()
  {
	try {
		$request = $this->request;
		$method = $request->getMethod();
		$table = $request->getVar('table');

		if($table == 'skripsi'){
			$skripsi = new SkripsiModel();
			$skripsi->deleteData($request->getVar('id'), $table);
		}else if($table == 'jurnal_dosen'){
			$skripsi = new JurnalDosenModel();
			$skripsi->deleteData($request->getVar('id'), $table);
		}else if($table == 'riset_dosen'){
			$skripsi = new RisetDosenModel();
			$skripsi->deleteData($request->getVar('id'), $table);
		}else if($table == 'mahasiswa'){
			$skripsi = new MahasiswaModel();
			$skripsi->deleteData($request->getVar('id'), $table);
		}
		else{
			$user = new \App\Models\UserModel();
			$user->deleteData($request->getVar('id'), $request->getVar('table'));
			if (file_exists($request->getVar('path'))) {
				unlink($request->getVar('path'));
			}
		}

		$response = [
			'status'   => 'success',
			'code'     => 200,
		];

		header('Content-Type: application/json');
	  	echo json_encode($response);
		exit;
	} catch (\Exception $e) {
		die($e->getMessage());
	}
  }

  public function getpdf(){
	$request = $this->request;
	$url = $request->getVar('url'); 
	
    $file_name = basename($url); 
	$basepath = './uploads/view/';

	if(!is_dir($basepath)){
		mkdir($basepath, 0777, true);
	}
	if(file_exists($basepath.$file_name)){
		unlink($basepath.$file_name);
	}
    file_put_contents($basepath.$file_name, file_get_contents($url));

	$response = [
		'data'   => $basepath.$file_name,
	];

	header('Content-Type: application/json');
	echo json_encode($response);
	exit;

  }

}
