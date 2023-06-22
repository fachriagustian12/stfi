<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;

class View extends \CodeIgniter\Controller
{

	protected $session;
	protected $request;

	function __construct(RequestInterface $request)
	{
		// $this->session = session();
		// $this->now = date('Y-m-d H:i:s');
		$this->request = $request;
		$this->logged = false;
		$this->data = array(
			'version' => \CodeIgniter\CodeIgniter::CI_VERSION,
			// 'baseURL' => BASE . '/public',
			'baseURL' => BASE,
			// 'userid' => $this->session->get('user_id'),
			// 'username' => $this->session->get('username'),
			// 'id' => $this->session->get('id'),
			// 'role' => $this->session->get('role'),
			// 'id_provinsi' => $this->session->get('id_provinsi'),
			// 'provinsi' => $this->session->get('provinsi'),
			// 'rolename' => $this->session->get('rolename'),
			// 'logged_in' => $this->session->get('logged_in'),
		);
	}

	public function index()
	{
		return redirect('dashboard');
	}

    public function dashboard()
	{

		if ($this->logged) {
			helper('form');
			// $statistik = new \App\Models\StatistikModel();
		  	// $data = $statistik->getdatapiutangdashboard();
		  	// $pembayaran = $statistik->getdatapembayarandashboard();
		  	// $status = $statistik->getdatastatusdashboard($this->session->get('role'), $this->session->get('id_provinsi'));
			
			// $this->data['here'] = 'dashboard';
			// $this->data['piutang'] = $data;
			// $this->data['pembayaran'] = $pembayaran;
			// $this->data['status'] = $status;
			$this->data['script'] = $this->data['baseURL'] . '/action-js/admin/index.js';
			return \Twig::instance()->display('admin/index.html', $this->data);
		} else {
			return redirect('login');
		}
	}

    public function login()
	{
		
		if ($this->logged) {
			return redirect('dashboard');
		} else {
			// helper('form');
			helper('url');
			$uri = current_url(true);
			// $message = $this->session->getFlashdata('msg');

			// if ($message) {
			// 	$this->data['message'] = $message;
			// }
			return \Twig::instance()->display('auth/login.html', $this->data);
		}
	}
}
?>