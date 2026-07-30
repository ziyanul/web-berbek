<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zanasi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Zanasi_model');
		$this->load->model('Varian_model');
		$this->load->model('Auth_model');
		$this->load->library('upload');
		$this->load->library('form_validation');

		if (!$this->Auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			// 'data' => $this->Zanasi_model->get_all(),
			'active_nav' => 'zanasi'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('zanasi/zanasi', $data);
		$this->load->view('partials/footer');
	}

	public function ajax_list()
	{
		$list = $this->Zanasi_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $row) {
			$no++;

			$action = '
                <a href="' . base_url('zanasi/detail/' . $row->uuid) . '" class="btn btn-sm btn-success shadow-sm btn-block">
                    <i class="fa fa-info fa-sm text-white mr-2"></i> Detail
                </a>';

			if ((int)$row->permintaan > (int)$row->total_print) {
				$action .= '
                <a href="' . base_url('zanasi/print/' . $row->uuid) . '" class="btn btn-sm btn-info shadow-sm btn-block">
                    <i class="fa fa-print fa-sm text-white mr-2"></i> Print
                </a>';
			}

			if ($this->session->userdata('type') == 1 || $this->session->userdata('type') == 2) {
				$action .= '
                <a href="' . base_url('zanasi/edit/' . $row->uuid) . '" class="btn btn-sm btn-warning shadow-sm btn-block">
                    <i class="fa fa-edit fa-sm text-white mr-2"></i> Edit
                </a>';
			}

			$data[] = array(
				$no,
				$row->tanggal,
				$row->rutin_label,
				$row->nama_varian,
				$row->kode,
				number_format($row->permintaan),
				number_format($row->total_print),
				$row->status,
				$action
			);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Zanasi_model->count_all(),
			"recordsFiltered" => $this->Zanasi_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function tambah()
	{
		$this->form_validation->set_rules($this->Zanasi_model->rules());

		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Zanasi_model->insert();

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				redirect('zanasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
				redirect('zanasi/tambah');
			}
		}

		$data = array(
			'varian' => $this->Varian_model->get_all(),
			'active_nav' => 'zanasi'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('zanasi/zanasi-tambah', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid)
	{
		$this->form_validation->set_rules($this->Zanasi_model->rules());

		if ($this->form_validation->run() === TRUE) {
			$update = $this->Zanasi_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil diubah.');
				redirect('zanasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal diubah.');
				redirect('zanasi/edit/' . $uuid);
			}
		}

		$data = array(
			'data'   => $this->Zanasi_model->get_by_uuid($uuid),
			'print'  => $this->Zanasi_model->get_print_by_zanasi_uuid($uuid),
			'total'  => $this->Zanasi_model->get_total_print($uuid),
			'varian' => $this->Varian_model->get_all(),
			'active_nav' => 'zanasi'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('zanasi/zanasi-edit', $data);
		$this->load->view('partials/footer');
	}

	public function print($uuid)
	{
		$rules = [
			[
				'field' => 'print',
				'label' => 'Jumlah Print',
				'rules' => 'required|numeric|greater_than[0]'
			],
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$insert = $this->Zanasi_model->print($uuid);

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data print berhasil disimpan.');
				redirect('zanasi/print/' . $uuid);
			} else {
				$this->session->set_flashdata('error_msg', 'Data print gagal disimpan.');
				redirect('zanasi/print/' . $uuid);
			}
		}

		$data = array(
			'data' => $this->Zanasi_model->get_by_uuid($uuid),
			'print' => $this->Zanasi_model->get_print_by_zanasi_uuid($uuid),
			'total' => $this->Zanasi_model->get_total_print($uuid),
			'active_nav' => 'zanasi'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('zanasi/zanasi-print', $data);
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			'data' => $this->Zanasi_model->get_by_uuid($uuid),
			'print' => $this->Zanasi_model->get_print_by_zanasi_uuid($uuid),
			'total' => $this->Zanasi_model->get_total_print($uuid),
			'active_nav' => 'zanasi'
		);

		$this->load->view('partials/head-form', $data);
		$this->load->view('zanasi/zanasi-detail', $data);
		$this->load->view('partials/footer');
	}
}
