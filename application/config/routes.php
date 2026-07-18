	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['pegawai/detail/(:any)'] = 'pegawai/detail/$1';
$route['image-upload'] = 'ImageUpload';
$route['image-upload/post']['post'] = "ImageUpload/uploadImage";

/* Departemen */
$route['departemen'] = 'departemen';
$route['departemen/tambah'] = 'departemen/tambah';
$route['departemen/edit/(:any)'] = 'departemen/edit/$1';
$route['departemen/get_all'] = 'departemen/get_all';


$route['home/edit/(:any)'] = 'home/edit_home/$1';

$route['pm/tpm/tambah'] = 'pm/tambah';
$route['pm/tpm/edit/(:any)'] = 'pm/edit/$1';
$route['pm/tpm/detail/(:any)'] = 'pm/detail/$1';
$route['pm/tpm/status/(:any)'] = 'pm/status/$1';
$route['pm/history/detail/(:any)'] = 'pm/detail/$1';
$route['pm/tpm/tindakan/(:any)'] = 'pm/tindakan/$1';
$route['pm/tpm/delete_kegiatan/(:any)'] = 'pm/delete_kegiatan/$1';

$route['gmp/tpm/tambah'] = 'gmp/tambah';
$route['gmp/tpm/edit/(:any)'] = 'gmp/edit/$1';
$route['gmp/tpm/detail/(:any)'] = 'gmp/detail/$1';
$route['gmp/history/detail/(:any)'] = 'gmp/detail/$1';
$route['gmp/tpm/status/(:any)'] = 'gmp/status/$1';
$route['gmp/tpm/delete_gmp/(:any)'] = 'gmp/delete_gmp/$1';

$route['am/tpm/tambah'] = 'am/tambah';
$route['am/tpm/edit/(:any)'] = 'am/edit/$1';
$route['am/tpm/detail/(:any)'] = 'am/detail/$1';
$route['am/history/detail/(:any)'] = 'am/detail/$1';
$route['am/tpm/status/(:any)'] = 'am/status/$1';
$route['am/tpm/delete_am/(:any)'] = 'am/delete_am/$1';

$route['monitor/tpm/tambah'] = 'monitor/tambah';
$route['monitor/tpm/edit/(:any)'] = 'monitor/edit/$1';
$route['monitor/tpm/tindakan/(:any)'] = 'monitor/tindakan/$1';
$route['monitor/tpm/detail/(:any)'] = 'monitor/detail/$1';
$route['monitor/tpm/status/(:any)'] = 'monitor/status/$1';
$route['monitor/history/detail/(:any)'] = 'monitor/detail/$1';
$route['partrequest/history/detail/(:any)'] = 'partrequest/detail/$1';

$route['maintenance'] = 'portal/maintenance';
$route['paperless'] = 'portal/paperless';
$route['yield'] = 'portal/yield';