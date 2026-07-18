<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Formula extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Formula_model');
		$this->load->model('Varian_model');
		$this->load->model('Bahan_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = array(
			'data' => $this->Formula_model->get_all(),
			'active_nav' => 'm_formula'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('formula/formula', $data);
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			'data' => $this->Formula_model->get_by_uuid($uuid),
			'active_nav' => 'm_formula'
		);
		
		$this->load->view('partials/head-yield', $data);
		$this->load->view('formula/formula-detail', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Formula_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			if ($this->input->post()) {
				$this->Formula_model->insert_master_detail($this->input->post());
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('formula');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('formula');
			}
		}

		$data = array(
			'varian'		=> $this->Varian_model->get_all(),
			'bahan'		=> $this->Bahan_model->get_all(),
			'active_nav' 	=> 'm_formula'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('formula/formula-tambah');
		$this->load->view('partials/footer');
	}
	public function edit($uuid) //harus ada parameternya
	{
		$rules = $this->Formula_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			if ($this->input->post()) {

				$this->Formula_model->update_master_detail(
					$uuid,
					$this->input->post()
				);

				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('formula/detail/'.$uuid);
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				redirect('formula/detail/'.$uuid);
			}
		}

		$data = array(
			'data' 			=> $this->Formula_model->get_by_uuid($uuid),
			'varian'		=> $this->Varian_model->get_all(),
			'bahan'			=> $this->Bahan_model->get_all(),
			'active_nav' 	=> 'm_formula'
		);

		$this->load->view('partials/head-yield', $data);
		$this->load->view('formula/formula-edit', $data);
		$this->load->view('partials/footer');
	}

	public function delete($uuid)
	{
		if ($this->Formula_model->delete($uuid))
		{
			$this->session->set_flashdata(
				'success_msg',
				'Data berhasil dihapus.'
			);
		}
		else
		{
			$this->session->set_flashdata(
				'error_msg',
				'Data gagal dihapus.'
			);
		}

		redirect('formula');
	}
}
