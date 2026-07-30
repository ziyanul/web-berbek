<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Rt_rjmesin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('Rt_rjmesin_model');
		$this->load->library('form_validation');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Rt_rjmesin_model->get_all(),
			'active_nav' => 'rt_rjmesin'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('rt_rjmesin/home', $data);
		$this->load->view('partials/footer');
	}

	public function tambahcek($kode_batch)
	{
		$rules = $this->Rt_rjmesin_model->rules();

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$nav = $this->Rt_rjmesin_model->get_by_code($kode_batch);
			$insert = $this->Rt_rjmesin_model->insert_mesin($kode_batch);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('rt_rjmesin/detail/' . $nav->t_planning_uuid);
			} else {

				$this->session->set_flashdata('error_msg', 'Terjadi kesalahan saat menyimpan data.');
				redirect('rt_rjmesin/detail/' . $nav->t_planning_uuid);
			}
		}

		$data = array(
			'nav' => $this->Rt_rjmesin_model->get_by_code($kode_batch),
			'data' => $this->Rt_rjmesin_model->get_mesin($kode_batch),
			'badpro' => $this->Rt_rjmesin_model->get_badpro(),
			'active_nav' => 'rt_rjmesin'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('rt_rjmesin/tambah');
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			'data' => $this->Rt_rjmesin_model->get_batch($uuid),
			'active_nav' => 'rt_rjmesin'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('rt_rjmesin/batch', $data);
		$this->load->view('partials/footer');
	}

	public function editreject($rm_uuid)
	{
		$rules = $this->Rt_rjmesin_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$redi = $this->Rt_rjmesin_model->get_reject_by_mesin($rm_uuid);
			$update = $this->Rt_rjmesin_model->update_reject($rm_uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di Ubah.');
				redirect('rt_rjmesin/detailreject/' . $redi[0]->kode_batch);
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di Ubah.');
				redirect('rt_rjmesin/detailreject/' . $redi[0]->kode_batch);
			}
		}

		$data = array(
			'data' => $this->Rt_rjmesin_model->get_reject_by_mesin($rm_uuid),
			'badpro' => $this->Rt_rjmesin_model->get_badpro(),
			'active_nav' => 'rt_rjmesin'
		);
		// echo "<pre>";
		// print_r($data);
		// echo '</pre>';
		$this->load->view('partials/head-form', $data);
		$this->load->view('rt_rjmesin/edit-reject');
		$this->load->view('partials/footer');
	}

	public function detailreject($MN_BATCH)
	{
		$data = array(
			'data_mesin' => $this->Rt_rjmesin_model->get_reject_by_batch($MN_BATCH),
			'badpro_headers' => $this->Rt_rjmesin_model->get_badpro_headers($MN_BATCH),
			'nav' => $this->Rt_rjmesin_model->get_by_code($MN_BATCH),
			'active_nav' => 'rt_rjmesin'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('rt_rjmesin/detail-batch', $data);
		$this->load->view('partials/footer');
	}

	public function get_mesin_by_counter($kode_batch)
	{
		$data = $this->Rt_rjmesin_model->get_mesin($kode_batch);
		echo json_encode($data);
	}

	public function form($planprod_uuid)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);

		$logo = base_url("assets/img/cpi-logo.jpg");
		$title = 'REJECT PER MESIN DI RETORT';
		$no_form = 'FR-PROD-04';
		$revisi = '0';
		$tanggal_efektif = '01 - 04 - 2016';
		$halaman = '1 dari 1';

		$data_mesin = $this->Rt_rjmesin_model->get_mesin_reject_by_plan($planprod_uuid);
		$badpro_headers = $this->Rt_rjmesin_model->get_badpro_headers_by_plan($planprod_uuid);
		$batches = $this->Rt_rjmesin_model->get_batches_by_plan($planprod_uuid);
		$plan = $this->Rt_rjmesin_model->get_plan_by_uuid($planprod_uuid);
		// Hitung total reject per batch dan kategori badpro
		$totals = [];
		foreach ($data_mesin as $mesin) {
			foreach ($mesin['batches'] as $batch => $badpro) {
				foreach ($badpro_headers as $header) {
					$key = $header->nama_badpro;
					$totals[$batch][$key] = ($totals[$batch][$key] ?? 0) + ($badpro[$key] ?? 0);
				}
			}
		}

		$data = [
			'data_mesin' => $data_mesin,
			'plan' => $plan,
			'badpro_headers' => $badpro_headers,
			'batches' => $batches,
			'totals' => $totals,
			'logo' => $logo,
			'title' => $title,
			'no_form' => $no_form,
			'revisi' => $revisi,
			'tanggal_efektif' => $tanggal_efektif,
			'halaman' => $halaman,
		];

		// // Tangkap tampilan sebagai string HTML
		$html = $this->load->view('partials/head-form-land', $data, true);

		$html .= $this->load->view('rt_rjmesin/form', $data, true);


		// Load HTML ke Dompdf
		$dompdf->loadHtml($html);
		$dompdf->setPaper('FOLIO', 'landscape');
		$dompdf->render();
		$dompdf->stream("$no_form $title", array("Attachment" => false));
	}

	public function approve_kr($plan_uuid)
	{
		$response = $this->Rt_rjmesin_model->update_kr($plan_uuid);

		if ($response) {
			$result = [
				'status' => true,
				'fullname' => $this->auth_model->current_user()->fullname // Ganti dengan data fullname pengguna saat ini
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Gagal memperbarui data'
			];
		}

		echo json_encode($result);
	}

	public function approve_spv($plan_uuid)
	{
		$response = $this->Rt_rjmesin_model->update_spv($plan_uuid);

		if ($response) {
			$result = [
				'status' => true,
				'fullname' => $this->auth_model->current_user()->fullname // Ganti dengan data fullname pengguna saat ini
			];
		} else {
			$result = [
				'status' => false,
				'message' => 'Gagal memperbarui data'
			];
		}

		echo json_encode($result);
	}
}
