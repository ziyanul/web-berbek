<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Speed extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Speed_model');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
		$this->load->model('Varian_model');
		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$pivot = $this->Speed_model->get_master_speed_pivot_dynamic();
		$data = array(
			'varian_list' => $pivot['varian_list'],
			'speed_list' => $pivot['data'],
			'active_nav' => 'masterspeed'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('speed/speed-filler', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Speed_model->rulesspeed();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$insert = $this->Speed_model->insertspeed();
			if ($insert) {

				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('speed');
			} else {
				redirect('speed');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			'mesin' => $this->Speed_model->get_all_mesin(),
			'varian' => $this->Varian_model->get_all(),
			'active_nav' => 'masterspeed'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('speed/tambah-speed', $data);
		$this->load->view('partials/footer');
	}

	public function edit($mesin_uuid)
	{
		$ruless = $this->Speed_model->rulesspeededit();
		$this->form_validation->set_rules($ruless);

		if ($this->form_validation->run() === TRUE) {

			$update_speed = $this->Speed_model->update_speed_per_mesin($mesin_uuid);
			if ($update_speed) {

				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('speed/');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('speed/');
			}
		}

		$data = array(
			'data' => $this->Speed_model->get_master_speed_by_mesin($mesin_uuid),

			'active_nav' => 'masterspeed'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('speed/edit-speed', $data);
		$this->load->view('partials/footer');
	}

}