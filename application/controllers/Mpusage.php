<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mpusage extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mpusage_model');
		$this->load->model('Formula_model');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');
		if (!$this->Auth_model->current_user()) {
			redirect('login');
		}
	}
	public function index()
	{
		$data = array(
			'data' => $this->Mpusage_model->get_all(),
			'active_nav' => 'mpusage'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('mpusage/mpusage', $data);
		$this->load->view('partials/footer');
	}
	public function input($uuid)
	{
		$rules = $this->Mpusage_model->rules();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$this->Mpusage_model->update_mp_usage($uuid);
			$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
			redirect('mpusage');
		}
		$mpusage = $this->Mpusage_model->get_by_uuid($uuid);
		$data = array(
			'data' => $mpusage,
			'formula' => $this->Formula_model->get_by_varian($mpusage->uuid_varian),
			'fvarian' => $this->Formula_model->get_formula_by_varian($mpusage->uuid_varian),
			'active_nav' => 'mpusage'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('mpusage/input', $data);
		$this->load->view('partials/footer');
	}
	public function get_total_rework()
	{
		$varian_uuid = $this->input->get('varian_uuid');
		$total = $this->Mpusage_model->get_total_rework_available($varian_uuid);
		echo json_encode([
			'total' => round($total, 2)
		]);
	}
}
