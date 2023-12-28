<?php namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;
use CodeIgniter\Files\File;

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
		$request		= $this->request;
		$param		= $request->getVar('param');
		
		$method			= $request->getMethod();
		$mahasiswa = new \App\Models\MahasiswaModel();
		if($method == 'post'){
				
				if($request->getVar('id')){
					$data = [
						'nama' 				=> $request->getVar('nama'),
						'npm' 				=> $request->getVar('npm'),
						'semester' 			=> $request->getVar('semester'),
						'prodi' 			=> $request->getVar('prodi'),
						'status_mahasiswa' => ($request->getVar('status_mahasiswa') == 'on') ? 1 : 0,
						'status_perwalian' => ($request->getVar('status_perwalian') == 'on') ? 1 : 0,
						'update_date' 	=> $this->now,
						'update_by' 	=> $this->session->get('id')
					];
					
					$mahasiswa->update($request->getVar('id'), $data);

					
				}else{
					$data = [
						'nama' => $request->getVar('nama'),
						'npm' => $request->getVar('npm'),
						'semester' => $request->getVar('semester'),
						'prodi' => $request->getVar('prodi'),
						'status_mahasiswa' => ($request->getVar('status_mahasiswa') == 'on') ? 1 : 0,
						'status_perwalian' => ($request->getVar('status_perwalian') == 'on') ? 1 : 0,
						'create_date' 	=> $this->now,
						'update_date' 	=> $this->now,
						'create_by' 	=> $this->session->get('id'),
						'update_by' 	=> $this->session->get('id')
					];
					
					$mahasiswa->insert($data);
					$lastid = $mahasiswa->insertID();

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
		
		if($method == 'post'){
				if($request->getVar('id')){
					$tanggal = $request->getVar('tanggal');
					$tanggal .= ' 00:00:00.000000';

					$data = [
						'nama'			=> $request->getVar('nama'),
						'no_kelas'		=> $request->getVar('no_kelas'),
						'matkul'		=> $request->getVar('matkul'),
						'status'		=> $request->getVar('status'),
						'jam_mulai'		=> $request->getVar('jam_mulai'),
						'jam_akhir'		=> $request->getVar('jam_akhir'),
						'tanggal'		=> $tanggal,
						'updated_at'	=> $this->now,
						'updated_by'		=> $this->session->get('id'),
					];
					$kelas->update($request->getVar('id'), $data);
				}else{
					$tanggal = $request->getVar('tanggal');
					$tanggal .= ' 00:00:00.000000';

					$data = [
						'nama' 			=> $request->getVar('nama'),
						'no_kelas' 		=> $request->getVar('no_kelas'),
						'matkul' 		=> $request->getVar('matkul'),
						'status' 		=> $request->getVar('status'),
						'jam_mulai' 	=> $request->getVar('jam_mulai'),
						'jam_akhir' 	=> $request->getVar('jam_akhir'),
						'tanggal' 		=> $tanggal,
						'created_at'	=> $this->now,
						'created_by'	=> $this->session->get('id'),
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
		$praktik 		= new \App\Models\PraktikumModel();
		
		if($method == 'post'){
			if($request->getVar('id')){
				$tanggal = $request->getVar('tanggal');
				$tanggal .= ' 00:00:00.000000';
				
					$data = [
						'ruangan_praktikum'			=> $request->getVar('ruangan_praktikum'),
						'mata_kuliah_praktikum'		=> $request->getVar('mata_kuliah_praktikum'),
						'nama_dosen'				=> $request->getVar('nama_dosen'),
						'status'					=> $request->getVar('status'),
						'jam_mulai'					=> $request->getVar('jam_mulai'),
						'jam_akhir'					=> $request->getVar('jam_akhir'),
						'tanggal'					=> $tanggal,
						'updated_at'				=> $this->now,
						'updated_by'				=> $this->session->get('id'),
					];
					$praktik->update($request->getVar('id'), $data);
				}else{
					$tanggal = $request->getVar('tanggal');
					$tanggal .= ' 00:00:00.000000';

					$data = [
						'ruangan_praktikum'			=> $request->getVar('ruangan_praktikum'),
						'mata_kuliah_praktikum' 	=> $request->getVar('mata_kuliah_praktikum'),
						'nama_dosen' 				=> $request->getVar('nama_dosen'),
						'status' 					=> $request->getVar('status'),
						'jam_mulai' 				=> $request->getVar('jam_mulai'),
						'jam_akhir' 				=> $request->getVar('jam_akhir'),
						'tanggal' 					=> $tanggal,
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
		$table = 'jadwal_praktikum';
		$user = new \App\Models\UserModel();
		$data = $user->getDosenPraktik($table);
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
		  $table	= $request->getVar('table');
		  $id	= $request->getVar('id');
		  $user = new \App\Models\UserModel();
		  $data = $user->getData($table, $id);
		  
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

  public function deletedata()
  {
	try {
		$request		= $this->request;
		$method			= $request->getMethod();
		$user = new \App\Models\UserModel();

		$user->deleteData($request->getVar('id'), $request->getVar('table'));
		if (file_exists($request->getVar('path'))) {
			unlink($request->getVar('path'));
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

}
