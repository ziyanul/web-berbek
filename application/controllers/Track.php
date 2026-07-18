<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Track extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Track_model');
		$this->load->model('Rj_filler_model');
		$this->load->library('form_validation');
		$this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');
	}

	public function index()
	{
		$data = array(
			'mesin' => $this->Rj_filler_model->get_mesin_filler()
		);

		$this->load->view('partials/head-view');
		$this->load->view('track/track', $data);
		$this->load->view('partials/footer');
}

public function rekap()
{
    $mesin_uuid = $this->input->post('mesin_uuid');
    $awal = $this->input->post('awal');
    $akhir = $this->input->post('akhir');

    if (!$mesin_uuid || !$awal || !$akhir) {
        echo '<div class="alert alert-danger">Semua input harus diisi.</div>';
        return;
    }

    // Debug input
    // var_dump($mesin_uuid, $awal, $akhir); exit;

    $result = $this->Track_model->get_data_by_mesin_and_range($mesin_uuid, $awal, $akhir);

    if ($result === false) {
        echo '<div class="alert alert-danger">Gagal mengambil data.</div>';
        return;
    }

    $data['result'] = $result;

    $data['judul_tabel'] = [
    'rj_filler'   => 'Reject Filler',
    'ch_rj_mesin' => 'Reject Cooking',
    'v_smfgmsn'   => 'Reject SMFG'
];


    $this->load->view('track/rekap_result', $data);
}


}