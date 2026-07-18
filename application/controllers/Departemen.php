<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departemen extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->model('Departemen_model');
		$this->load->library('form_validation');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{

		$data = array(
			'departemen' => $this->Departemen_model->get_all(),
			'active_nav' => 'departemen',
			'title' => 'Departemen | CPI Absensi Online'
		);

		$this->load->view('partials/head.php', $data);
		$this->load->view('departemen/departemen.php', $data);
		$this->load->view('partials/footer.php');
	}

	// public function tambah()
	// {
	// 	$rules = $this->Departemen_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if($this->form_validation->run() == TRUE){
	// 		$insert = $this->Departemen_model->insert();
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'Data Departemen berhasil di simpan');
	// 			redirect('departemen');
	// 		} else {
	// 			$this->session->set_flashdata('error_msg', 'Data Departemen gagal di simpan');
	// 			redirect('departemen');
	// 		}
	// 	}
	// 	$data = array('active_nav' => 'departemen',
	// 		'title' => 'Departemen | CPI Absensi Online');

	// 	$this->load->view('partials/head.php', $data);
	// 	$this->load->view('departemen/departemen-tambah.php');
	// 	$this->load->view('partials/footer.php');
	// }

	// public function edit($uuid)
	// {
	// 	$rules = $this->Departemen_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if($this->form_validation->run() == TRUE){
	// 		$insert = $this->Departemen_model->update($uuid);
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'Data Departemen berhasil di update');
	// 			redirect('departemen');
	// 		} else {
	// 			$this->session->set_flashdata('error_msg', 'Data Departemen gagal di update');
	// 			redirect('departemen');
	// 		}
	// 	}

	// 	$data = array('departemen' => $this->Departemen_model->get_by_uuid($uuid),
	// 		'active_nav' => 'departemen',
	// 		'title' => 'Departemen | CPI Absensi Online');

	// 	$this->load->view('partials/head.php', $data);
	// 	$this->load->view('departemen/departemen-edit.php', $data);
	// 	$this->load->view('partials/footer.php');
	// }

	public function get_all()
	{
		$departemen = $this->Departemen_model->get_all();

		if ($departemen) {
			$data = array(
				'status' => true, 
				'data' => $departemen
			);

			print_r(json_encode($data));
		} else{
			$data = array(
				'status' => false
			);

			print_r(json_encode($data));
		}
	}
}