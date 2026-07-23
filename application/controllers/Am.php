	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Am extends CI_Controller {

		public function __construct()
		{
			parent::__construct();

			$this->load->model('Area_model');
			$this->load->model('Mesin_model');
			$this->load->model('Am_model');
			$this->load->library('upload');
			$this->load->library('form_validation');
			$this->load->model('Auth_model');
			if(!$this->Auth_model->current_user()){
				redirect('login');
			}
		}

		public function index()
		{
			$data = array(
			// 'data' => $this->Am_model->get_all(),
				'data' => $this->Am_model->get_am(),
				'active_nav' => 'am'
			);

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/am', $data);
			$this->load->view('partials/footer');
		}

		public function history()
		{

			$data = array(
				'data' => $this->Am_model->get_history(),
				'active_nav' => 'am-history'
			);

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/am-history', $data);
			$this->load->view('partials/footer');
		}

		public function tpm()
		{

			$data = array(
				'data' => $this->Am_model->get_tpm(),
				'active_nav' => 'am-tpm'
			);

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/am-tpm', $data);
			$this->load->view('partials/footer');
		}

		public function tambah()
		{
			$items = $this->input->post('items');

    // LOAD VIEW kalau belum submit
			if (!$this->input->post()) {

				$data = [
					'area' => $this->Am_model->get_area(),
					'active_nav' => ($this->uri->segment(2)=='tpm'?'am-tpm':'am')
				];

				$this->load->view('partials/head-maintenance', $data);
				$this->load->view('am/am-tambah', $data);
				$this->load->view('partials/footer');
				return;
			}

    // VALIDASI DATA KOSONG
			if (!$items) {
				$this->session->set_flashdata('error_msg', 'Tidak ada data kegiatan.');
				redirect($this->uri->segment(2)=='tpm'?'am/tpm':'am');
				return;
			}

    // VALIDASI MANUAL
			$valid = false;

			foreach ($items as $i) {
				if (!empty($i['kegiatan_uuid']) && !empty($i['target']) && $i['target'] > 0) {
					$valid = true;
					break;
				}
			}

			if (!$valid) {
				$this->session->set_flashdata('error_msg', 'Minimal 1 kegiatan harus punya target.');
				redirect($this->uri->segment(2)=='tpm'?'am/tpm':'am');
				return;
			}

    // INSERT
			$insert = $this->Am_model->insert_massal($items);

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
			}

			redirect($this->uri->segment(2)=='tpm'?'am/tpm':'am');
		}

		public function tambaharea()
		{
			$rules = [
				[
					'field' => 'area',
					'label' => 'Area',
					'rules' => 'required'
				],
			];
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {

				$insert = $this->Am_model->insertarea();
				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
					redirect('am/area');

				} else {
					redirect('am/area');
					$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				}
			}

			$data = array(
			// 'area' => $this->Area_model->get_all(),
			// 'part' => $this->part_model->get_all(),
		// 	'mesin' => $this->Mesin_model->get_all(),
				'active_nav' => 'am-area'
			);

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/tambah-area', $data);
			$this->load->view('partials/footer');
		}

		public function tambahmesin()
		{
			$rules = [
				[
					'field' => 'area',
					'label' => 'Area',
					'rules' => 'required'
				],
				[
					'field' => 'mesin',
					'label' => 'SubArea',
					'rules' => 'required'
				]
			];
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {

				$insert = $this->Am_model->insertmesin();
				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
					redirect('am/area');

				} else {
					redirect('am/area');
					$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				}
			}

			$data = array(
				'area' => $this->Am_model->get_area(),
			// 'part' => $this->part_model->get_all(),
		// 	'mesin' => $this->Mesin_model->get_all(),
				'active_nav' => 'am-area'
			);

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/tambah-mesin', $data);
			$this->load->view('partials/footer');
		}

		public function tambahkegiatan()
		{
    // Jika submit form
			if ($this->input->method() === 'post') {

				$kegiatan = $this->input->post('kegiatan');

				if (!is_array($kegiatan)) {
					$kegiatan = [];
				}

				$filled = array_filter($kegiatan, function($item){
					return trim($item) !== '';
				});

				if (empty($filled)) {
					$this->session->set_flashdata('error_msg', 'Minimal isi 1 kegiatan.');
					redirect('am/tambahkegiatan');
					return;
				}

				$insert = $this->Am_model->insertkegiatan();

				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				} else {
					$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
				}

				redirect('am/data');
				return;
			}

    // Jika buka halaman form
			$data = [
				'area'       => $this->Area_model->get_all(),
				'mesin'      => $this->Am_model->get_mesin(),
				'active_nav' => 'am-area'
			];

			$this->load->view('partials/head-maintenance', $data);
			$this->load->view('am/tambah-kegiatan', $data);
			$this->load->view('partials/footer');
		}
		public function tindakan($uuid)
		{
			$rules = [
				[
					'field' => 'pelaksana',
					'label' => 'Pelaksana',
					'rules' => 'required'
				]
			];
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === TRUE) {
				$config = array(
					'upload_path' => "./upload/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite' => TRUE,
					'max_size' => "2048",
					'encrypt_name' => TRUE
				);
				$this->upload->initialize($config);
				$dok_after = '';
				if (!$this->upload->do_upload('dokumentasi_after')) {
					$error = $this->upload->display_errors();
				} else {
					$data = $this->upload->data();
					$dok_after = $data['file_name'];
				}
			$update = $this->Am_model->tindakan($uuid, $dok_after); // di ambil dari fungsi yang sudah di set di model mesin
			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('am/tpm');
				} else {
					redirect('am');
				}	
			} else { // Jika update sama dg false
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('am/tpm');
				} else {
					redirect('am');
				}	
			}
		}
		
		$data = array(
			'data' => $this->Am_model->get_detail($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'am-tpm':'am'),
		);

		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/am-tindakan', $data);
		$this->load->view('partials/footer');	
	}

	public function edit($uuid)
	{
		$rules = [
			[
				'field' => 'target',
				'label' => 'Jadwal',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$update = $this->Am_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('am/tpm');
				} else {
					redirect('am');
				}	
			} else { // Jika update sama dg false
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('am/tpm');
				} else {
					redirect('am');
				}	
			}
		}
		
		$data = array(
			'area' => $this->Am_model->get_area(),
			'mesin' => $this->Am_model->get_mesin(),
			'kegiatan' => $this->Am_model->get_kegiatan(),
			'data' => $this->Am_model->get_detail($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'am-tpm':'am')
		);
		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/am-edit', $data);
		$this->load->view('partials/footer');	
	}

	public function editarea($uuid) //harus ada parameternya
	{
		$rules = [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true

			$update = $this->Am_model->updatearea($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('am/area');
			} else { // Jika update sama dg false
				redirect('am/area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			'area' => $this->Am_model->get_area_name($uuid),
			'active_nav' => 'am-area'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/edit-area', $data);
		$this->load->view('partials/footer');
	}

	public function editmesin($uuid) //harus ada parameternya
	{
		$rules = [
			[
				'field' => 'mesin',
				'label' => 'mesin',
				'rules' => 'required'
			],

		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true

			$update = $this->Am_model->updatemesin($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('am/area');
			} else { // Jika update sama dg false
				redirect('am/area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			// 'area' => $this->Am_model->get_area_name($uuid),
			'mesin' => $this->Am_model->mesin_by_area($uuid),
			'active_nav' => 'am-area'
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/edit-mesin', $data);
		$this->load->view('partials/footer');
	}

	public function editkegiatan($uuid) //harus ada parameternya
	{
		$rules = [
			[
				'field' => 'kegiatan',
				'label' => 'Kegiatan',
				'rules' => 'required'
			],

		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true

			$update = $this->Am_model->updatekegiatan($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('am/data');
			} else { // Jika update sama dg false
				redirect('am/data');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			// 'area' => $this->Am_model->get_area_name($uuid),
			'kegiatan' => $this->Am_model->get_kegiatan_name($uuid),
			'active_nav' => 'am-data'
		);
		

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/edit-kegiatan', $data);
		$this->load->view('partials/footer');
	}

	public function status($uuid)
	{
		$rules = [
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Catatan',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$status = $this->input->post('status');
			$catatan = $this->input->post('catatan');
			

			if ($status == 1) {
				$insertnew = $this->Am_model->insertnew($uuid);
				$insertStatus = $this->Am_model->insertstatus($uuid);
				if ($insertStatus && $insertnew) {
					$this->session->set_flashdata('success_msg', 'Pengajuan berhasil di ACC');
					redirect(($this->uri->segment(2) == 'tpm' ? 'am/tpm' : 'am'));
				}
			} elseif ($status == 2) {
				$insertStatus = $this->Am_model->insertstatus($uuid);

				if ($insertStatus) {
					$this->session->set_flashdata('success_msg', 'Pengajuan berhasil di ACC');
					redirect(($this->uri->segment(2) == 'tpm' ? 'am/tpm' : 'am'));
				}
			} else {
				$this->session->set_flashdata('error_msg', 'Invalid status value');
			}
		}

		$data = array (
			'data' => $this->Am_model->get_by_uuid($uuid),
			'status' => $this->Am_model->get_status_by_am_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'am-tpm':'am'),
		);
		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/am-status', $data);
		$this->load->view('partials/footer');
	}

	public function data()
	{
		$data = array(
			// 'data' => $this->Am_model->get_all(),
			'kegiatan' => $this->Am_model->get_kegiatan(),
			'active_nav' => 'am-data'
		);

		

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/data', $data);
		$this->load->view('partials/footer');
	}

	public function area()
	{
		$data = array(
			// 'data' => $this->Am_model->get_all(),
			'area' => $this->Am_model->get_area(),
			// 'mesin' => $this->Am_model->get_mesin(),
			// 'kegiatan' => $this->Am_model->get_kegiatan(),
			'active_nav' => 'am-area'
		);

		

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/area', $data);
		$this->load->view('partials/footer');
	}

	public function mesin($uuid)
	{
		$data = array(
			'mesin' => $this->Am_model->get_all_mesin($uuid),
			'area' => $this->Am_model->get_area_name($uuid),
			'active_nav' => 'am-area'
		);
		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/mesin', $data);
		$this->load->view('partials/footer');
	}

	public function kegiatan($uuid)
	{
		$data = array(
			'kegiatan' => $this->Am_model->get_all_kegiatan($uuid),
			'mesin' => $this->Mesin_model->get_mesin_name($uuid),
			// 'area' => $this->Am_model->get_area_name($uuid),
			'active_nav' => 'am-data'
		);
		
		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/kegiatan', $data);
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			
			'data' => $this->Am_model->get_detail($uuid),
			'status' => $this->Am_model->get_status_by_am_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'am-tpm':($this->uri->segment(2)=='history'?'am-history':'am')),
		);

		$this->load->view('partials/head-maintenance', $data);
		$this->load->view('am/am-detail', $data);
		$this->load->view('partials/footer');
	}

	

	// public function get_mesin_name($uuid)
	// {
	// 	$data = $this->Am_model->get_mesin_by_area($uuid);
	// 	print_r(json_encode($data));
	// }

	public function get_kegiatan_by_mesin($mesin_uuid)
	{
		$data = $this->Am_model->get_kegiatan_by_mesin($mesin_uuid);
		print_r(json_encode($data));
	}

	public function get_kegiatan_available($mesin_uuid)
	{
		$data = $this->Am_model->get_kegiatan_available($mesin_uuid);
		echo json_encode($data);
	}

	public function save_massal()
	{
		$items = $this->input->post('items'); 
    // array kegiatan

		$result = $this->Am_model->insert_massal($items);

		echo json_encode([
			'status' => $result
		]);
	}

	public function get_kegiatan_name($uuid)
	{
		$data = $this->Am_model->get_kegiatan_name($uuid);
		print_r(json_encode($data));
	}

	public function get_mesin_by_area($area_uuid)
	{
		$data = $this->Am_model->get_mesin_by_area($area_uuid);
		print_r(json_encode($data));
	}

	public function delete_kegiatan($uuid) 
	{

		$this->Am_model->delete_kegiatan($uuid);
		redirect('am/data/');
	}

	public function delete_am($uuid) 
	{
		$this->Am_model->delete_am($uuid);
		redirect($this->uri->segment(2) == 'tpm' ? 'am/tpm' : 'am');
	}

	public function update_kegiatan_ajax()
	{
		$uuid     = $this->input->post('uuid');
		$kegiatan = $this->input->post('kegiatan');

		$result = $this->Am_model->update_kegiatan_ajax($uuid, $kegiatan);

		echo json_encode($result);
	}

}