	<?php
	defined('BASEPATH') or exit('No direct script access allowed');

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
	$route['default_controller'] = 'portal';
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


	/* Bahan Baku */
	$route['bahan_mp/tambah'] = 'bahan_mp/tambah';
	$route['bahan_mp/get_item_by_jenis'] = 'bahan_mp/get_item_by_jenis';
	$route['bahan_mp/detail_(:any)'] = 'bahan_mp/detail/$1';
	$route['bahan_mp/detail_(:any)/tambah'] = 'bahan_mp/tambah';
	$route['bahan_mp/form_(:any)'] = 'bahan_mp/form/$1';
	$route['bahan_mp/(:any)'] = 'bahan_mp/kirim/$1';

	$route['bahan_packing/tambah'] = 'bahan_packing/tambah';
	$route['bahan_packing/get_item_packing/'] = 'bahan_packing/get_item_packing/';
	$route['bahan_packing/detail_(:any)'] = 'bahan_packing/detail/$1';
	$route['bahan_packing/detail_(:any)/tambah'] = 'bahan_packing/tambah';
	$route['bahan_packing/form_(:any)'] = 'bahan_packing/form/$1';
	$route['bahan_packing/(:any)'] = 'bahan_packing/kirim/$1';

	$route['bahan_sanitasi/tambah'] = 'bahan_sanitasi/tambah';
	$route['bahan_sanitasi/get_item_sanitasi/'] = 'bahan_sanitasi/get_item_sanitasi/';
	$route['bahan_sanitasi/detail_(:any)'] = 'bahan_sanitasi/detail/$1';
	$route['bahan_sanitasi/detail_(:any)/tambah'] = 'bahan_sanitasi/tambah';
	$route['bahan_sanitasi/form_(:any)'] = 'bahan_sanitasi/form/$1';
	$route['bahan_sanitasi/(:any)'] = 'bahan_sanitasi/kirim/$1';

	$route['bahan_filler'] = 'bahan_baku/filler';
	$route['bahan_baku/get_item_filler/'] = 'bahan_baku/get_item_filler/';
	$route['bahan_filler/tambah'] = 'bahan_baku/tambahfiller';
	$route['bahan_filler/detail_(:any)'] = 'bahan_baku/detailfiller/$1';
	$route['bahan_filler/detail_(:any)/tambah'] = 'bahan_baku/tambahfiller';
	$route['bahan_filler/form_(:any)'] = 'bahan_baku/formfiller/$1';
	$route['bahan_filler/(:any)'] = 'bahan_baku/kirimfiller/$1';

	$route['bahan_sparepart'] = 'bahan_baku/sparepart';
	$route['bahan_sparepart/tambah'] = 'bahan_baku/tambahpart';
	$route['bahan_sparepart/detail_(:any)'] = 'bahan_baku/detailpart/$1';
	$route['bahan_sparepart/detail_(:any)/tambah'] = 'bahan_baku/tambahpart';
	$route['bahan_sparepart/form_(:any)'] = 'bahan_baku/formpart/$1';
	$route['bahan_sparepart/(:any)'] = 'bahan_baku/kirimpart/$1';


	/*Cek Mesin*/
	$route['cekmesin_mp'] = 'cekmesin/mp';
	$route['cekmesin_mp/(:any)'] = 'cekmesin/checklistmp/$1';
	$route['cekmesin_mp/detailmp/(:any)/(:any)'] = 'cekmesin/detailcekmesinmp/$1/$2';
	$route['cekmesin/get_mesin_by_area/(:any)/(:any)'] = 'cekmesin/get_mesin_by_area/$1/$2';
	$route['cekmesin_mp/form-(:any)/(:any)'] = 'cekmesin/printmp/$1/$2';

	$route['cekmesin_filler/(:any)'] = 'cekmesin_filler/checklist_awalproses/$1';
	$route['cekmesin_filler/detail-(:any)/(:any)'] = 'cekmesin_filler/detail_awalproses/$1/$2';
	$route['cekmesin_filler/get_mesin_by_area/(:any)/(:any)'] = 'cekmesin_filler/get_mesin_by_area/$1/$2';
	$route['cekmesin_filler/form-(:any)/(:any)'] = 'cekmesin_filler/form_awalproses/$1/$2';
	$route['cekmesin_fillerbatch/detail-(:any)'] = 'cekmesin_fillerbatch/detail/$1';
	// $route['cekmesin_fillerbatch/(:any)'] = 'cekmesin_fillerbatch/ceklist_batch/$1';

	$route['cekmesin_susun/(:any)'] = 'cekmesin_susun/checklist/$1';
	$route['cekmesin_susun/detail-(:any)/(:any)'] = 'cekmesin_susun/detail/$1/$2';
	$route['cekmesin_susun/get_mesin_by_area/(:any)/(:any)'] = 'cekmesin_susun/get_mesin_by_area/$1/$2';
	$route['cekmesin_susun/form-(:any)/(:any)'] = 'cekmesin_susun/print/$1/$2';

	$route['cekmesin_retort/(:any)'] = 'cekmesin_retort/checklist/$1';
	$route['cekmesin_retort/detail-(:any)/(:any)'] = 'cekmesin_retort/detail/$1/$2';
	$route['cekmesin_retort/get_mesin_by_area/(:any)/(:any)'] = 'cekmesin_retort/get_mesin_by_area/$1/$2';
	$route['cekmesin_retort/form-(:any)/(:any)'] = 'cekmesin_retort/form/$1/$2';

	/*Pergantian Varian*/
	$route['pergantian_varian_retort'] = 'pergantian_varian/retort';
	$route['pergantian_varian_retort/tambah'] = 'pergantian_varian/tambah';
	$route['pergantian_varian_retort/detail/(:any)'] = 'pergantian_varian/detail_retort/$1';
	$route['pergantian_varian_retort/edit/(:any)'] = 'pergantian_varian/edit_retort/$1';
	$route['pergantian_varian_retort/form/(:any)'] = 'pergantian_varian/form_retort/$1';
	// $route['pergantian_varian_retort/approval/(:any)/(:any)'] = 'pergantian_varian/approval_retort/$1/$2';

	$route['pergantian_varian_packing'] = 'pergantian_varian/packing';
	$route['pergantian_varian_packing/tambah/(:any)'] = 'pergantian_varian/tambah_packing/$1';
	$route['pergantian_varian_packing/detail/(:any)/(:any)'] = 'pergantian_varian/detail_packing/$1/$2';
	$route['pergantian_varian_packing/edit/(:any)'] = 'pergantian_varian/edit_packing/$1';
	$route['pergantian_varian_packing/form_2/(:any)/(:any)'] = 'pergantian_varian/form_packing/$1/$2';
	$route['pergantian_varian/approval_packing/(:any)/(:any)'] = 'pergantian_varian/approval_packing/$1/$2';
