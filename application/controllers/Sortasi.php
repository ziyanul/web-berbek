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
		$this->load->model('Varian_model');
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
			'jenis_sortasi' => $this->Sortasi_model->get_jenis_sortasi(),
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
			'data' => $sortasi,
			'batch' => $this->Sortasi_model->get_batch_edit($sortasi->tbatch_uuid),
			'jenis_sortasi' =>	$this->Sortasi_model->get_jenis_sortasi(),
			'wip' => $this->Sortasi_model->get_wip_for_edit($sortasi->tbatch_uuid, $uuid),
			'output' => $this->Sortasi_model->get_output_by_sortasi($uuid),
			'badpro' => $this->Sortasi_model->get_badpro('SORTASI'),
			'badpro_input' => $this->Sortasi_model->get_badpro_by_ref($uuid),
			'batch_info' =>	$this->Sortasi_model->get_batch_info($sortasi->tbatch_uuid),
			'mesin' => $this->Sortasi_model->get_mesin_batch($sortasi->tbatch_uuid),
			'active_nav' => 'sortasi'
		];
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/edit', $data);
		$this->load->view('partials/footer');
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
	public function detail($tbatch_uuid)
	{
		if (empty($tbatch_uuid)) {
			redirect('sortasi');
		}
		$batch = $this->Sortasi_model->get_batch_detail($tbatch_uuid);
		if (!$batch) {
			$this->session->set_flashdata(
				'error_msg',
				'Data batch tidak ditemukan.'
			);
			redirect('sortasi');
		}
		$data = [
			'batch' => $batch,
			'history' => $this->Sortasi_model->get_sortasi_history_by_batch($tbatch_uuid),
			'wip_ledger' => $this->Sortasi_model->get_wip_ledger_by_batch($tbatch_uuid),
			'badpro' => $this->Sortasi_model->get_badpro_by_batch($tbatch_uuid),
			'active_nav' => 'sortasi'
		];
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/detail', $data);
		$this->load->view('partials/footer');
	}
	/*
	*============================================
	JENIS SORTASI
	*============================================
	*/
	public function jenis()
	{
		$rules_jenis = $this->Sortasi_model->rules_jenis();
		$this->form_validation->set_rules($rules_jenis);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Sortasi_model->insert_jenis();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Jenis Sortasi berhasil di tambah.');
				redirect('sortasi/jenis');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Jenis Sortasi gagal di tambah.');
				redirect('sortasi/jenis');
			}
		}
		$data = array(
			'data' => $this->Sortasi_model->get_all_jenis(),
			'active_nav' => 'sortasi-jenis'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/jenis', $data);
		$this->load->view('partials/footer');
	}
	public function edit_jenis($uuid)
	{
		$rules_jenis = $this->Sortasi_model->rules_jenis();
		$this->form_validation->set_rules($rules_jenis);
		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Sortasi_model->update_jenis($uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Jenis Sortasi berhasil di ubah.');
				redirect('sortasi/jenis');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Jenis Sortasi gagal di ubah.');
				redirect('sortasi/jenis/' . $uuid);
			}
		}
		$data = array(
			'data' => $this->Sortasi_model->get_jenis_by_uuid($uuid),
			'active_nav' => 'sortasi-jenis'
		);
		$this->load->view('partials/head-yield', $data);
		$this->load->view('sortasi/jenis-edit', $data);
		$this->load->view('partials/footer');
	}
	public function get_wip_batch($uuid)
	{
		$data =
			$this->Sortasi_model
			->get_wip_batch($uuid);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	/*============================================
	* CUCI
	*============================================
	*/
	public function cuci()
	{
		$data = [
			'data'       => $this->Sortasi_model->get_cuci(),
			'active_nav' => 'sortasi'
		];
		$this->load->view(
			'partials/head-yield',
			$data
		);
		$this->load->view(
			'sortasi/cuci',
			$data
		);
		$this->load->view(
			'partials/footer'
		);
	}
	public function cuci_tambah()
	{
		$data = [
			'varian'     => $this->Varian_model->get_all(),
			'active_nav' => 'sortasi'
		];
		$this->load->view(
			'partials/head-yield',
			$data
		);
		$this->load->view(
			'sortasi/cuci-tambah',
			$data
		);
		$this->load->view(
			'partials/footer'
		);
	}
	public function get_cuci_by_varian($varian_uuid)
	{
		$data = $this->Sortasi_model
			->get_cuci_by_varian($varian_uuid);
		echo json_encode($data);
	}
	public function cuci_simpan()
	{
		$insert = $this->Sortasi_model->insert_cuci();
		if ($insert) {
			$this->session->set_flashdata(
				'success_msg',
				'Data Cuci berhasil disimpan.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Data Cuci gagal disimpan.'
			);
		}
		redirect('sortasi/cuci');
	}
	public function cuci_edit($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID Cuci tidak valid.'
			);
			redirect('sortasi/cuci');
			return;
		}
		$data_cuci = $this->Sortasi_model
			->get_cuci_by_uuid($uuid);
		if (!$data_cuci) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Cuci tidak ditemukan.'
			);
			redirect('sortasi/cuci');
			return;
		}
		/*
     * Cek apakah batch hasil sudah dipakai Sortasi.
     */
		if (
			$this->Sortasi_model
			->cuci_batch_sudah_dipakai(
				$data_cuci->tbatch_uuid_hasil
			)
		) {
			$this->session->set_flashdata(
				'error_msg',
				'Cuci tidak dapat diedit karena batch hasil sudah digunakan pada proses Sortasi.'
			);
			redirect('sortasi/cuci');
			return;
		}
		$data = [
			'data' => $data_cuci,
			'detail' => $this->Sortasi_model
				->get_cuci_details($uuid),
			'varian' => $this->Sortasi_model
				->get_varian(),
			'active_nav' => 'sortasi'
		];
		$this->load->view(
			'partials/head-yield',
			$data
		);
		$this->load->view(
			'sortasi/cuci-edit',
			$data
		);
		$this->load->view(
			'partials/footer'
		);
	}
	/**
	 * =========================================================
	 * UPDATE CUCI
	 * =========================================================
	 */
	public function cuci_update($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID Cuci tidak valid.'
			);
			redirect('sortasi/cuci');
			return;
		}
		$result = $this->Sortasi_model
			->update_cuci($uuid);
		if ($result['status']) {
			$this->session->set_flashdata(
				'success_msg',
				$result['message']
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				$result['message']
			);
		}
		redirect('sortasi/cuci');
	}
	/**
	 * =========================================================
	 * HAPUS CUCI
	 * =========================================================
	 */
	public function cuci_hapus($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID Cuci tidak valid.'
			);
			redirect('sortasi/cuci');
			return;
		}
		$result = $this->Sortasi_model
			->delete_cuci($uuid);
		if ($result['status']) {
			$this->session->set_flashdata(
				'success_msg',
				$result['message']
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				$result['message']
			);
		}
		redirect('sortasi/cuci');
	}
}
