<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Sortasi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('Sortasi_model');
		$this->load->library('form_validation');
		if (!$this->Auth_model->current_user()) {
			redirect('login');
		}
	}
	public function index()
	{
		$data = array(
			'data' => $this->Sortasi_model->get_all(),
			'active_nav' => 'sortasi'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/sortasi', $data);
		$this->load->view('partials/footer');
	}
	public function tambah()
	{
		$rules = $this->Sortasi_model->rules();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Sortasi_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Sortasi berhasil di tambah.');
				redirect('sortasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Sortasi gagal di tambah.');
				redirect('sortasi');
			}
		}
		$data = array(
			'batch'      => $this->Sortasi_model->get_batch(),
			'badpro'     => $this->Sortasi_model->get_badpro('SORTASI'),
			'active_nav' => 'sortasi'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/sortasi-tambah', $data);
		$this->load->view('partials/footer');
	}
	public function edit($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID tidak valid.'
			);
			redirect('sortasi');
		}
		$rules = $this->Sortasi_model->rules();
		$this->form_validation->set_rules(
			$rules
		);
		/*
     * =====================================================
     * SIMPAN
     * =====================================================
     */
		if (
			$this->form_validation->run()
			=== TRUE
		) {
			$update =
				$this->Sortasi_model
				->update($uuid);
			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Sortasi berhasil diubah.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Sortasi gagal diubah.'
				);
			}
			redirect('sortasi/');
		}
		/*
     * =====================================================
     * DATA SORTASI
     * =====================================================
     */
		$sortasi =
			$this->Sortasi_model
			->get_by_uuid($uuid);
		if (!$sortasi) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Sortasi tidak ditemukan.'
			);
			redirect('sortasi');
		}
		/*
     * =====================================================
     * DATA VIEW
     * =====================================================
     */
		$data = [
			'data' =>
			$sortasi,
			'batch' =>
$this->Sortasi_model
    ->get_batch_edit(
        $sortasi->tbatch_uuid
    ),
			'badpro' =>
			$this->Sortasi_model
				->get_badpro('SORTASI'),
			'badpro_input' =>
			$this->Sortasi_model
				->get_badpro_by_ref($uuid),
			'batch_info' =>
			$this->Sortasi_model
				->get_batch_info(
					$sortasi->tbatch_uuid
				),
			'mesin' =>
			$this->Sortasi_model
				->get_mesin_batch(
					$sortasi->tbatch_uuid
				),
			'active_nav' =>
			'sortasi'
		];
		$this->load->view(
			'partials/head-yield',
			$data
		);
		$this->load->view(
			'sortasi/edit',
			$data
		);
		$this->load->view(
			'partials/footer'
		);
	}
	public function hapus($uuid)
	{
		if ($this->Sortasi_model->delete($uuid)) {
			$this->session->set_flashdata(
				'success_msg',
				'Data Sortasi berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Data Sortasi gagal dihapus.'
			);
		}
		redirect('sortasi');
	}
	public function get_batch_info($uuid)
	{
		echo json_encode(
			$this->Sortasi_model->get_batch_info($uuid)
		);
	}
	public function get_mesin_batch($uuid)
	{
		echo json_encode(
			$this->Sortasi_model->get_mesin_batch($uuid)
		);
	}
	public function detail($uuid)
	{
		if (empty($uuid)) {
			redirect('sortasi');
		}
		$data = [
			'data'          => $this->Sortasi_model->get_by_uuid($uuid),
			'badpro'        => $this->Sortasi_model->get_badpro_by_ref($uuid),
			'badpro_summary' => $this->Sortasi_model->get_badpro_summary_by_ref($uuid),
			'active_nav'    => 'sortasi'
		];
		if (!$data['data']) {
			$this->session->set_flashdata(
				'error_msg',
				'Data tidak ditemukan.'
			);
			redirect('sortasi');
		}
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/detail', $data);
		$this->load->view('partials/footer');
	}
}
