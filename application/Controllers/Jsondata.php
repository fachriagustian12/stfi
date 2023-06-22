<?php namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Controller\BaseController;

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
			if($param){
				if($request->getVar('id')){
					$data = [
						'nama'			=> $request->getVar('nama'),
						'email'			=> $request->getVar('email'),
						'usernm'		=> $request->getVar('usernm'),
						'pass'			=> $request->getVar('pass'),
						'kontak'		=> $request->getVar('kontak'),
						'id_provinsi'	=> $request->getVar('id_provinsi'),
						'updated_at'	=> date('Y-m-d H:i:s'),
						'updated_by'	=> $this->session->get('id'),
					];
					$user->updateSimponi($request->getVar('id'), $data);
				}else{
					$data = [
						'nama'			=> $request->getVar('nama'),
						'email'			=> $request->getVar('email'),
						'usernm'		=> $request->getVar('usernm'),
						'pass'			=> $request->getVar('pass'),
						'kontak'		=> $request->getVar('kontak'),
						'id_provinsi'	=> $request->getVar('id_provinsi'),
						'created_at'	=> date('Y-m-d H:i:s'),
						'created_by'	=> $this->session->get('id'),
					];

					$user->insertSimponi($data);
				}
			}else{
				if($request->getVar('id')){
					$data = [
						'username' 		=> $request->getVar('username'),	
						'id_role' 		=> $request->getVar('id_role'),
						'id_provinsi' 	=> $request->getVar('id_provinsi'),
						'status' 		=> 1
					];
					
					if($request->getVar('password')){
						$data['password'] = password_hash($request->getVar('password'), PASSWORD_DEFAULT);
					}
					$user->update($request->getVar('id'), $data);
					$this->logModel->insert([
						'tgl' => date('Y-m-d H:i:s'), 
						'username' => $this->session->get('username'), 
						'keterangan' => "Mengubah data user ".$request->getVar('username'),
					]);
					
				}else{
					$data = [
						'username' 		=> $request->getVar('username'),	
						'password' 		=> password_hash($request->getVar('password'), PASSWORD_DEFAULT),
						'id_role' 		=> $request->getVar('id_role'),
						'id_provinsi' 	=> $request->getVar('id_provinsi'),
						'status' 		=> 1
					];


					$user->insert($data);
					$this->logModel->insert([
						'tgl' => date('Y-m-d H:i:s'), 
						'username' => $this->session->get('username'), 
						'keterangan' => "Menambah data user ".$request->getVar('username'),
					]);
				}
			}
		}
		redirect($param?'usersimponi':'user','refresh');
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
		if($param){
			$user->deleteSimponi($request->getVar('id'));
		}else{
			$user->delete($request->getVar('id'));
			$this->logModel->insert([
				'tgl' => date('Y-m-d H:i:s'), 
				'username' => $this->session->get('username'), 
				'keterangan' => "Menghapus data user ".$request->getVar('username'),
			]);
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

}
