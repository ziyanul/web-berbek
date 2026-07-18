<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Badpro extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->model('Badpro_model');
		$this->load->model('Area_model');
		$this->load->library('form_validation');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Badpro_model->get_all(),
			'active_nav' => 'm-badpro'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('badpro/master');
		$this->load->view('partials/footer');
	}

	public function tambahmaster()
	{
		$rules = $this->Badpro_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {

			$insert = $this->Badpro_model->insert_master();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('badpro/');
			} else {
				redirect('badpro/');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			'proses' => $this->Badpro_model->get_proses(),
			'active_nav' => 'm-badpro'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('badpro/tambah-master');
		$this->load->view('partials/footer');
	}

	public function editmaster($uuid)
	{
		$rules = $this->Badpro_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Badpro_model->update_master($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil diubah.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal diubah.');
			}
			redirect('badpro/');
		}

		$data = array(
			'data' => $this->Badpro_model->get_by_uuid($uuid),
			'area' => $this->Area_model->get_all(),
			'active_nav' => 'm-badpro'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('badpro/edit-master');
		$this->load->view('partials/footer');
	}

	public function hapus_badpro($uuid)
	{
		$delete = $this->Badpro_model->hapus_master($uuid);
		if ($delete) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data gagal dihapus.');
			echo json_encode(array('status' => FALSE));
		}
	}

}