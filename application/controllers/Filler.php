<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Filler extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Filler_model');
		$this->load->model('Mesin_model');
		$this->load->model('Counter_model');
		$this->load->model('Varian_model');
		$this->load->model('Auth_model');
		$this->load->library('form_validation');
		if (!$this->Auth_model->current_user()) {
			redirect('login');
		}
	}
	public function planning()
	{
		$data = array(
			'data' => $this->Filler_model->get_plan_data(),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/plan-filler', $data);
		$this->load->view('partials/footer');
	}
	public function detailplan($uuid)
	{
		$data = array(
			'data' => $this->Filler_model->get_plan_uuid($uuid),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/detail-plan', $data);
		$this->load->view('partials/footer');
	}
	public function tambahplan()
	{
		$rules = $this->Filler_model->rules();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Filler_model->save_plan_with_speed();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
			}
			redirect('filler/planning');
		}
		$data = array(
			'active_nav' => 'filler',
			'data' => $this->Varian_model->get_all()
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/tambah-plan', $data);
		$this->load->view('partials/footer');
	}
	public function editplan($uuid)
	{
		$rules = $this->Filler_model->rules();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$update = $this->Filler_model->update_plan($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('filler/planning');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('filler/planning');
			}
		}
		$data = array(
			'data' => $this->Filler_model->get_plan_uuid($uuid),
			'varian' => $this->Varian_model->get_all(),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/edit-plan');
		$this->load->view('partials/footer');
	}
	public function detailloses($t_planning_uuid, $mesin_uuid)
	{
		$data = array(
			'data' => $this->Filler_model->get_mesin_status($t_planning_uuid, $mesin_uuid),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/detail-loses', $data);
		$this->load->view('partials/footer');
	}
	public function detaildown($t_planning_uuid, $mesin_uuid)
	{
		$data = array(
			'data' => $this->Filler_model->get_mesin_status($t_planning_uuid, $mesin_uuid),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/detail-downtime', $data);
		$this->load->view('partials/footer');
	}
	public function detailmesin($t_planning_uuid, $mesin_uuid)
	{
		$mesin = $this->Filler_model->get_mesin_name($mesin_uuid);
		$data = array(
			'data' => $this->Filler_model->get_mesin_status($t_planning_uuid, $mesin_uuid),
			't_planning_uuid' => $t_planning_uuid,
			'mesin_uuid' => $mesin_uuid,
			'mesin' => $mesin,
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/detail-mesin', $data);
		$this->load->view('partials/footer');
	}
	public function updateketerangan()
	{
		$id = $this->input->post('id');
		$t_planning_uuid = $this->input->post('t_planning_uuid');
		$mesin_uuid = $this->input->post('mesin_uuid');
		$keterangan = $this->input->post('f_keterangan');
		$update_success = $this->Filler_model->update_keterangan($id, $t_planning_uuid, $mesin_uuid, $keterangan);
		if ($update_success) {
			$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
		}
		redirect('filler/detailmesin/' . $t_planning_uuid . '/' . $mesin_uuid);
	}
	public function tambahquality($planning_uuid, $mesin_uuid)
	{
		$rules = [
			[
				'field' => 'jumlah',
				'label' => 'Reject',
				'rules' => 'required|numeric'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$update = $this->Filler_model->update_quality($planning_uuid, $mesin_uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('filler/performance/' . $planning_uuid);
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('filler/performance/' . $planning_uuid);
			}
		}
		$data = array(
			'quality' => $this->Filler_model->get_speed_data($planning_uuid, $mesin_uuid),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/tambah-quality', $data);
		$this->load->view('partials/footer');
	}
	public function tambahdowntime($uuid)
	{
		$rules = [
			[
				'field' => 'jumlah',
				'label' => 'Menit',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$planning_uuid = $this->Filler_model->get_t_speed($uuid);
			$insert = $this->Filler_model->insert_downtime($uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('filler/performance/' . $planning_uuid->t_planning_uuid);
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal menyimpan data.');
				redirect('filler/performance/' . $planning_uuid->t_planning_uuid);
			}
		}
		$data = array(
			'quality' => $this->Filler_model->get_t_speed($uuid),
			'rincian' => $this->Filler_model->get_keterangan_downtime($uuid),
			'active_nav' => 'filler'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/tambah-downtime', $data);
		$this->load->view('partials/footer');
	}
	public function performance($uuid)
	{
		$mesin_uuid = $this->input->get('mesin_uuid');
		$batch_uuid = $this->input->get('batch_uuid');
		$data = [
			'data' => $this->Filler_model->get_counter_by_t_planning_uuid(
				$uuid,
				$mesin_uuid,
				$batch_uuid
			),
			'mesin' => $this->Filler_model->get_mesin_by_plan($uuid),
			'batch' => $this->Filler_model->get_batch_by_plan($uuid),
			'active_nav' => 'filler'
		];
		$this->load->view('partials/head-yield', $data);
		$this->load->view('filler/performa-filler', $data);
		$this->load->view('partials/footer');
	}
	public function hapusplan($uuid)
	{
		$this->Filler_model->delete_plan($uuid);
		redirect('filler/planning');
	}
	public function hapusdowntime($uuid)
	{
		$this->Filler_model->delete_downtime($uuid);
		redirect('filler/planning');
	}
	public function get_batch_ajax()
	{
		$planning_uuid = $this->input->post('planning_uuid');
		$mesin_uuid    = $this->input->post('mesin_uuid');
		echo json_encode(
			$this->Filler_model->get_batch_filter(
				$planning_uuid,
				$mesin_uuid
			)
		);
	}
}