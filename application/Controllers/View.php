<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\BukuModel;
use App\Models\FrontModel;
use App\Models\KegiatanModel;
use CodeIgniter\HTTP\RequestInterface;

class View extends \CodeIgniter\Controller
{

	protected $session;
	protected $request;

	function __construct(RequestInterface $request)
	{
		$this->session = session();
		// $this->now = date('Y-m-d H:i:s');
		$this->request = $request;
		$this->logged = $this->session->get('logged_in');
		$this->data = array(
			'version' => \CodeIgniter\CodeIgniter::CI_VERSION,
			// 'baseURL' => BASE . '/public',
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

	//FRONTEND

	public function index()
	{
		return redirect('home');
	}

	// public function home()
	// {
	// 	helper('url');
	// 	$uri = current_url(true);

	// 	return \Twig::instance()->display('front/home.html');
	// }

	public function home()
	{
		helper('url');
		$uri = current_url(true);

		return \Twig::instance()->display('front/menu.html');
	}

	public function login()
	{

		if ($this->logged) {
			return redirect('dashboard');
		} else {
			helper('form');
			helper('url');
			$uri = current_url(true);
			$message = $this->session->getFlashdata('msg');

			if ($message) {
				$this->data['message'] = $message;
			}
			return \Twig::instance()->display('auth/login.html', $this->data);
		}
	}

	public function layanan_informasi_mahasiswa()
	{
		helper('url');
		$uri = current_url(true);
		// $mhs = new FrontModel();

		// $this->data['mhs'] = $mhs->get();

		return \Twig::instance()->display('front/layanan_informasi/mahasiswa.html', $this->data);
	}

	public function layanan_informasi_kelas()
	{
		helper('url');
		$uri = current_url(true);
		$this->data['hari'] = ['SENIN', "SELASA", 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
		// $mhs = new FrontModel();

		// $this->data['mhs'] = $mhs->get();

		return \Twig::instance()->display('front/kelas.html', $this->data);
	}

	public function layanan_informasi_dosen()
	{
		helper('url');
		$uri = current_url(true);

		return \Twig::instance()->display('front/layanan_informasi/dosen.html', $this->data);
	}

	public function layanan_informasi_buku()
	{
		helper('url');
		$uri = current_url(true);
		$model = new BukuModel();
		$get = $this->request->getVar('search');

		if ($get != "") {
			$this->data['results'] = $model->like('title', $get)->paginate(10, 'new_pagination');
		} else {
			$this->data['results'] = $model->paginate(10, 'new_pagination');
		}

		// Menampilkan link pagination
		$this->data['pager'] = $model->pager;
		$this->data['links'] = $this->data['pager']->links('new_pagination', 'new_pagination');
		return \Twig::instance()->display('front/buku_digital.html', $this->data);
	}

	public function layanan_berita()
	{
		helper('url');
		$uri = current_url(true);

		$model = new BeritaModel();
		$get = $this->request->getVar('search');
		$kat = $this->request->getVar('kategori');

		if ($get != "" || $kat != '') {
			if ($kat == "") {
				$this->data['results'] = $model->where('status', 1)->like('title', $get)->paginate(10, 'new_pagination');
			} else {
				$this->data['results'] = $model->where('status', 1)->where('kategori', $kat)->like('title', $get)->paginate(10, 'new_pagination');
			}
			$getval = $get;
			$getkat = $kat;
		} else {
			$this->data['results'] = $model->where('status', 1)->paginate(10, 'new_pagination');
			$getval = '';
			$getkat = '';
		}

		$this->data['getval'] = $getval;
		$this->data['getkat'] = $getkat;
		// Menampilkan link pagination
		$this->data['pager'] = $model->pager;
		$this->data['links'] = $this->data['pager']->links('new_pagination', 'new_pagination');
		return \Twig::instance()->display('front/berita.html', $this->data);
	}

	public function detail_berita($id)
	{
		helper('url');
		$uri = current_url(true);
		$model = new BeritaModel();
		$this->data['berita'] = $model->find($id);
		$this->data['allberita'] = $model->where('id !=', $id)->findAll(5, 0);

		return \Twig::instance()->display('front/detailberita.html', $this->data);
	}

	public function layanan_agenda()
	{
		helper('url');
		$uri = current_url(true);
		$get = $this->request->getVar('kegiatan');
		$model = new KegiatanModel();

		if ($get != "") {
			$this->data['results'] = $model->like('kegiatan', $get)->paginate(10, 'new_pagination');
		} else {
			$this->data['results'] = $model->paginate(10, 'new_pagination');
		}

		// Menyimpan hasil paginasi ke dalam variabel data
		// var_dump($this->data['results']);
		// Menampilkan link pagination
		$this->data['pager'] = $model->pager;
		$this->data['links'] = $this->data['pager']->links('new_pagination', 'new_pagination');
		return \Twig::instance()->display('front/agenda.html', $this->data);
	}

	public function detail_agenda($id)
	{
		helper('url');
		$uri = current_url(true);
		$model = new KegiatanModel();
		$this->data['kegiatan'] = $model->find($id);
		$this->data['allkegiatan'] = $model->where('id !=', $id)->findAll(5, 0);
		return \Twig::instance()->display('front/detailagenda.html', $this->data);
	}


	// BACKEND

	public function dashboard()
	{

		if ($this->logged) {
			helper('form');
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/index.js';
			return \Twig::instance()->display('admin/index.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_mahasiswa()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/data_mahasiswa.js';
			return \Twig::instance()->display('admin/mahasiswa/data_mahasiswa.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_dosen()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/data_dosen.js';
			return \Twig::instance()->display('admin/dosen/data_dosen.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_kampus()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/data_kampus.js';
			return \Twig::instance()->display('admin/kampus/data_kampus.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_jadwal()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/data_jadwal.js';
			return \Twig::instance()->display('admin/jadwal/data_jadwal.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_buku()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/data_buku.js';
			return \Twig::instance()->display('admin/buku/data_buku.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_slider()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/content/data_slider.js';
			return \Twig::instance()->display('admin/content/data_slider.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_berita()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/content/data_berita.js';
			return \Twig::instance()->display('admin/content/data_berita.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_kelas()
	{
		if ($this->logged) {
			helper('form');
			$user = new \App\Models\UserModel();

			$this->data['data_dosen']	= $user->getDosen();
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/content/data_kelas.js';
			return \Twig::instance()->display('admin/content/data_kelas.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function data_jadwal_praktikum()
	{
		if ($this->logged) {
			helper('form');
			$user = new \App\Models\UserModel();

			$this->data['data_dosen']	= $user->getDosen();
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/content/data_jadwal_praktikum.js';
			return \Twig::instance()->display('admin/content/data_jadwal_praktikum.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function pnbp()
	{
		if ($this->logged) {
			helper('form');
			$statistik = new \App\Models\StatistikModel();
			$data = $statistik->getdatariwayatpnbp();
			$this->data['here'] = 'pnbp';
			$this->data['chart'] = $data;
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/pnbp.js';
			return \Twig::instance()->display('admin/pnbp.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function user()
	{

		if ($this->logged) {
			helper('form');
			$user = new \App\Models\UserModel();

			$this->data['here'] 			= 'user';
			$this->data['data_provinsi'] 	= $user->getprovinsi();
			$this->data['data_role']		= $user->getrole();
			var_dump($this->data['data_role']);die;

			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/user/user.js';
			return \Twig::instance()->display('admin/user/index.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function logss()
	{
		if ($this->logged) {
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/user/data_log.js';
			return \Twig::instance()->display('admin/user/data_log.html', $this->data);
		} else {
			return redirect('login');
		}
	}

	public function user_simponi()
	{

		if ($this->logged) {
			helper('form');
			$user = new \App\Models\UserModel();

			$this->data['here'] 			= 'usersimponi';
			$this->data['data_provinsi'] 	= $user->getprovinsi();
			$this->data['data_role']		= $user->getrole();

			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/user/user-simponi.js';
			return \Twig::instance()->display('admin/user/index-simponi.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function log()
	{

		if ($this->logged) {
			helper('form');

			$this->data['here'] 			= 'log';
			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/user/log.js';
			return \Twig::instance()->display('admin/user/log.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function infobox()
	{

		if ($this->logged) {
			helper('form');

			$request	= $this->request;
			$detail 	= $request->getVar('detail');

			$this->data['here'] 			= 'infobox';
			$this->data['box'] 				= $detail;
			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/infobox.js';
			return \Twig::instance()->display('admin/infobox.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function komunikasi()
	{

		if ($this->logged) {
			helper('form');
			$request	= $this->request;
			$id_tiket 	= $request->getVar('detail');
			$komunikasi = new \App\Models\KomunikasiModel();

			if ($id_tiket) {
				$this->data['detail_tiket'] 	= $komunikasi->gettiket(null, $this->session->get('role'), $id_tiket);
				$this->data['diskusi_tiket'] 	= $komunikasi->getdiskusitiket($id_tiket);
			} else {
				$this->data['data_tiket'] 		= $komunikasi->gettiket($this->session->get('user_id'), $this->session->get('role'));
			}

			$this->data['here'] 			= 'komunikasi';
			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/komunikasi.js';
			return \Twig::instance()->display('admin/komunikasi.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function wasdal()
	{

		if ($this->logged) {
			helper('form');
			$request	= $this->request;
			$id_tiket 	= $request->getVar('detail');
			$komunikasi = new \App\Models\KomunikasiModel();

			$this->data['here'] 			= 'wasdal';
			$this->data['sub'] 				= $request->uri->getSegment(2);
			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/komunikasi.js';
			return \Twig::instance()->display('admin/komunikasi.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}

	public function cicilan()
	{

		if ($this->logged) {
			helper('form');
			$request	= $this->request;
			$cicilan = new \App\Models\CicilanModel();
			$billing = new \App\Models\BillingModel();
			$user = new \App\Models\UserModel();
			if ($request->uri->getSegment(1) == 'detailcicilan') {
				$this->data['detail'] 	= 'detail';
				$this->data['data_rumah'] 	= $cicilan->getdetailrumahcicilan($request->uri->getSegment(2));
				$this->data['data_cicilan'] = $cicilan->getdetailcicilanangsuran($request->uri->getSegment(2));
				$this->data['data_denda'] 	= $cicilan->getdetailcicilandenda($request->uri->getSegment(2));
				$this->data['data_billing'] = $billing->getbilling($request->uri->getSegment(2));
			} else if ($request->uri->getSegment(1) == 'detailbilling') {

				$this->data['detail_billing'] = $billing->getdetailbilling($request->uri->getSegment(2));
				$this->data['detail_pembayaran_billing'] = $billing->getdetailpembayaranbilling($request->uri->getSegment(2));
			} else {
				$this->data['data_provinsi'] 	= $user->getprovinsi();
				$this->data['data_lembaga'] 	= $user->getlembaga();
			}

			// echo '<pre>';
			// print_r($this->data);die;
			$this->data['here'] 			= 'cicilan';
			$this->data['script'] 			= $this->data['baseURL'] . '/action-js/admin/cicilan/cicilan.js';
			return \Twig::instance()->display('admin/cicilan/cicilan.html', $this->data);
		} else {
			return redirect('dashboard');
		}
	}
}
