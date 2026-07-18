<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_area extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('area_model');
		$this->load->model('mesin_model');
		$this->load->model('sub_area_model');
		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->sub_area_model->get_all(),
			'active_nav' => 'subarea'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sub-area/sub-area', $data);
		$this->load->view('partials/footer');
	}

	public function detail($area_uuid)
	{
		$data = array(
			'data' => $this->sub_area_model->get_sub_by_area($area_uuid),
			'active_nav' => 'subarea'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('sub-area/detail', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'lokasi',
				'label' => 'SubArea',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			
			$insert = $this->sub_area_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('sub_area');
				
			} else {
				redirect('sub_area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'area' => $this->area_model->get_all(),
			'active_nav' => 'subarea'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('sub-area/tambah', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid) 
	{
		$rules = [
			[
				'field' => 'lokasi',
				'label' => 'Lokasi',
				'rules' => 'required'
			],
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) { 
			$update = $this->sub_area_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('sub_area');
			} else {
				redirect('sub_area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'lokasi' => $this->sub_area_model->lokasi_by_area($uuid),
			'active_nav' => 'subarea'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('sub-area/edit', $data);
		$this->load->view('partials/footer');
	}

	public function get_lokasi_by_area($area_uuid)
	{
		$data = $this->sub_area_model->get_lokasi_by_area($area_uuid);
		print_r(json_encode($data));
	}

	public function hapus($uuid) 
	{
			$hapus = $this->sub_area_model->hapus($uuid);
			if ($hapus) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di hapus.');
				redirect('sub_area');
			} else {
				redirect('sub_area');
				$this->session->set_flashdata('error_msg', 'Data gagal di hapus.');
			}
	}
}