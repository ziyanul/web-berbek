<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Mesin_model');
		$this->load->model('Area_model');
		$this->load->model('Part_model');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}


	public function index()
	{
		$data = array(
			'data' => $this->Mesin_model->get_all(),
			'active_nav' => 'mesin'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('mesin/mesin', $data);
		$this->load->view('partials/footer');
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('mesin')->result();
	}

	public function tambah()
	{
		$rules = $this->Mesin_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
	
			$insert = $this->Mesin_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
				redirect('mesin');
			} else {
				redirect('mesin');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}


		$data = array(
			'area' => $this->Area_model->get_all(),
			'active_nav' => 'mesin'
		);

	
		$this->load->view('partials/head', $data);
		$this->load->view('mesin/mesin-tambah', $data);
		$this->load->view('partials/footer');
	}
	
	public function edit($uuid)
	{
		$rules = [
			[
				'field' => 'mesin',
				'label' => 'Mesin',
				'rules' => 'required'
			],
			[
				'field' => 'rhupdate',
				'label' => 'RH Terbaru',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true
	
			$update = $this->Mesin_model->update($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
				redirect('mesin');
			} else { // Jika update sama dg false
				redirect('mesin');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}

		$data = array(
			'data' => $this->Mesin_model->get_mesin_name($uuid),
			'active_nav' => 'mesin'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('mesin/mesin-edit', $data);
		$this->load->view('partials/footer');
	}

	public function get_mesin_name($uuid)
	{
		$data = $this->Mesin_model->get_mesin_name($uuid);

		print_r(json_encode($data));
	}

	public function detail($uuid)
	{
		$data = array(
			'mesin' => $this->Mesin_model->get_mesin_name($uuid),
			'data' => $this->Part_model->get_part($uuid),
			'active_nav' => 'mesin'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('mesin/mesin-detail', $data);
		$this->load->view('partials/footer');
	}
}