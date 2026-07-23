<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Rr_cooking extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('Rr_cooking_model');
		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Rr_cooking_model->get_home(),
			// 'masak_data' => $this->Rr_cooking_model->get_masak_data(),
			'active_nav' => 'rr-cooking'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('rr-cooking/home',$data);
		$this->load->view('partials/footer');
	}

	public function tambah($uuid)
{
    $rules = $this->Rr_cooking_model->rules();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {
        $data = $this->Rr_cooking_model->get_masak_data($uuid);
        $this->Rr_cooking_model->insert_reject_cooking($uuid, $data->MR_KOPROD);
        $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
        redirect('rr_cooking');
    }

    $data = array(
        'data' => $this->Rr_cooking_model->get_masak_data($uuid),
        'active_nav' => 'rr-cooking'
    );

    $this->load->view('partials/head', $data);
    $this->load->view('rr-cooking/tambah', $data);
    $this->load->view('partials/footer');
}


	public function edit($uuid)
	{
		$rules = $this->Rr_cooking_model->rules1();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Rr_cooking_model->update_reject_cooking($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('Rr_cooking');
			} else {
				redirect('Rr_cooking');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$data = array(
			'data' => $this->Rr_cooking_model->get_masak_retort_uuid($uuid),
			// 'info' => $this->Rr_cooking_model->get_masak_data($uuid),
			'active_nav' => 'rr-cooking'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('rr-cooking/edit');
		$this->load->view('partials/footer');
	}

	public function detail($date, $mr_uuid_varian)
	{
		$data = array(
			'masak_data' => $this->Rr_cooking_model->getMasakData($date, $mr_uuid_varian),
			'info' => $this->Rr_cooking_model->get_info_detail($date, $mr_uuid_varian),
			'active_nav' => 'rr-cooking'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('rr-cooking/detail',$data);
		$this->load->view('partials/footer');
	}

	public function form($date, $mr_uuid_varian)
	{
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'DejaVu Sans');
		$dompdf = new Dompdf($options);

		$logo = base_url("assets/img/cpi-logo.jpg");
		$title = 'REJECT COOKING RETORT';
		$no_form = 'FR-PROD-05';
		$revisi = '1';
		$tanggal_efektif = '01 - 04 - 2016';
		$halaman = '1 dari 1';

		$data = [
			'masak_data' => $this->Rr_cooking_model->getMasakData($date, $mr_uuid_varian),
			'info' => $this->Rr_cooking_model->get_info_detail($date, $mr_uuid_varian),
			'logo' => $logo,
			'title' => $title,
			'no_form' => $no_form,
			'revisi' => $revisi,
			'tanggal_efektif' => $tanggal_efektif,
			'halaman' => $halaman,
		];

		$html = $this->load->view('partials/head-form-land', $data, true);
		
		$html .= $this->load->view('rr-cooking/form', $data, true);
		
		$dompdf->loadHtml($html);
		$dompdf->setPaper('FOLIO', 'landscape');
		$dompdf->render();
		$dompdf->stream("$no_form $title", array("Attachment" => false));
	}

	public function approve_kr($tanggal, $varian_uuid)
{
    $response = $this->Rr_cooking_model->update_kr($tanggal, $varian_uuid);

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

public function approve_spv($tanggal, $varian_uuid)
{
    $response = $this->Rr_cooking_model->update_spv($tanggal, $varian_uuid);

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