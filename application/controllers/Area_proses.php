<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_proses extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->model('Area_proses_model');
		$this->load->library('form_validation');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Area_proses_model->get_all(),
			'active_nav' => 'area'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('area-proses/area', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Area_proses_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
	
			$insert = $this->Area_proses_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Area berhasil di simpan.');
				redirect('area_proses');
			} else {
				redirect('area_proses');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}

		$data = array(
			'active_nav' => 'area'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('area-proses/area-tambah');
		$this->load->view('partials/footer');
	}
	public function edit($uuid) //harus ada parameternya
	{
		$rules = $this->Area_proses_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { // kondisi jika form validation true
	
			$update = $this->Area_proses_model->update($uuid); // di ambil dari fungsi yang sudah di set di model mesin

			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
				redirect('area_proses');
			} else { // Jika update sama dg false
				redirect('area_proses');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}

		$data = array(
			'data' => $this->Area_proses_model->get_by_uuid($uuid),
			'active_nav' => 'area'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('area-proses/area-edit', $data);
		$this->load->view('partials/footer');
	}

    public function hapus($uuid)
    {
        $data = $this->Area_proses_model->delete($uuid);
        if ($data) {
            $this->session->set_flashdata('success_msg', 'Data Area berhasil di hapus.');
            redirect('area_proses');
        } else {
            $this->session->set_flashdata('error_msg', 'Data Area gagal di hapus.');
            redirect('area_proses');
        }
    }

}