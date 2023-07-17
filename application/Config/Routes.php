<?php

namespace Config;

/**
 * --------------------------------------------------------------------
 * URI Routing
 * --------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Typically there is a one-to-one relationship between a URL string
 * and its corresponding controller class/method. The segments in a
 * URL normally follow this pattern:
 *
 *    example.com/class/method/id
 *
 * In some instances, however, you may want to remap this relationship
 * so that a different class/function is called than the one
 * corresponding to the URL.
 *
 */

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(BASEPATH . 'Config/Routes.php')) {
	require BASEPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('View');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->set404Override(function() {
// 	echo view('404');die;
// });
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// FRONT END
$routes->add('/', 'View::home');
$routes->add('home', 'View::home');
$routes->add('menu', 'View::menu');
$routes->add('layanan_informasi_mahasiswa', 'View::layanan_informasi_mahasiswa');
$routes->add('layanan_informasi_dosen', 'View::layanan_informasi_dosen');
$routes->add('layanan_informasi_buku', 'View::layanan_informasi_buku');
$routes->add('layanan_informasi_buku', 'View::layanan_informasi_buku');
// BERITA
$routes->add('layanan_berita', 'View::layanan_berita');
$routes->add('detailberita/(:any)', 'View::detail_berita');
// AGENDA
$routes->add('layanan_agenda', 'View::layanan_agenda');
$routes->add('detailagenda/(:any)', 'View::detail_agenda');


// LOGIN
$routes->add('login', 'View::login');

// BACKEND
$routes->add('dashboard', 'View::dashboard');
$routes->add('data_mahasiswa', 'View::data_mahasiswa');
$routes->add('data_dosen', 'View::data_dosen');
$routes->add('data_kampus', 'View::data_kampus');
$routes->add('data_jadwal', 'View::data_jadwal');
$routes->add('data_buku', 'View::data_buku');
$routes->add('data_user', 'View::user');
$routes->add('data_slider', 'View::data_slider');


// $routes->add('/', 'View::login');

// $routes->add('login', 'View::login');
// $routes->add('dashboard', 'View::dashboard');
$routes->add('pnbp', 'View::pnbp');
$routes->add('user', 'View::user');
$routes->add('usersimponi', 'View::user_simponi');
$routes->add('log', 'View::log');
$routes->add('infobox', 'View::infobox');
$routes->add('komunikasi', 'View::komunikasi');
$routes->add('wasdal/(:any)', 'View::wasdal');
$routes->add('cicilan', 'View::cicilan');
$routes->add('detailcicilan/(:any)', 'View::cicilan');
$routes->add('detailbilling/(:any)', 'View::cicilan');
 
$routes->add('auth', 'Auth::auth');
$routes->add('reg', 'Auth::reg');
$routes->add('logout', 'Auth::logout');

$routes->post('getalluser', 'Jsondata::getalluser');
$routes->post('getuser', 'Jsondata::getuser');
$routes->post('adduser', 'Jsondata::adduser');
$routes->post('deleteuser', 'Jsondata::deleteuser');
$routes->post('getlog', 'Jsondata::getlog');
$routes->post('getpenyesuaiangrafik', 'Jsondata::getpenyesuaiangrafik');
$routes->post('addpenyesuaiangrafik', 'Jsondata::addpenyesuaiangrafik');
$routes->post('getbox', 'Jsondata::getbox');
$routes->post('replytiket', 'Jsondata::replytiket');
$routes->get('deletetiket', 'Jsondata::deletetiket');
$routes->post('getcicilan', 'Jsondata::getcicilan');
$routes->post('getwilayah', 'Jsondata::getwilayah');
$routes->post('ubahpembayaran', 'Jsondata::ubahpembayaran');
$routes->post('pembayaran', 'Jsondata::pembayaran');
$routes->post('kodebilling', 'Jsondata::kodebilling');

$routes->post('getdata', 'Jsondata::getdata');
$routes->post('deletedata', 'Jsondata::deletedata');
$routes->post('addslider', 'Jsondata::addslider');
$routes->post('addmahasiswa', 'Jsondata::addmahasiswa');
$routes->post('adddosen', 'Jsondata::adddosen');
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
