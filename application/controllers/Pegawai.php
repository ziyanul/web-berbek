<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Pegawai_model');
		$this->load->model('Departemen_model');
		$this->load->library('form_validation');
		// $this->load->library('upload');
		$this->load->model('Auth_model');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pegawai' => $this->Pegawai_model->get_all(),
			'active_nav' => 'pegawai',
			'title' => 'Pegawai | CPI Berbek'
		);

		$this->load->view('partials/head.php', $data);
		$this->load->view('pegawai/pegawai.php', $data);
		$this->load->view('partials/footer.php');
	}

	public function tambah()
	{
		$rules = $this->Pegawai_model->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == TRUE){

			$insert = $this->Pegawai_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('pegawai');
			} else {
				redirect('pegawai');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}

		$dept = $this->Departemen_model->get_all();
		$data = array(
			'dept' => $dept,
			'active_nav' => 'pegawai',
			'title' => 'Pegawai | CPI Berbek'
		);


		$this->load->view('partials/head.php', $data);
		$this->load->view('pegawai/pegawai-tambah.php', $data);
		$this->load->view('partials/footer.php');
	}

	public function subrole($uuid)
	{
		$rules1 = [
			[
				'field' => 'sub_role',
				'label' => 'Pilih Sub role',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules1);

		if ($this->form_validation->run() === TRUE) {
			$pegawai = $this->Pegawai_model->get_by_uuid($uuid);
			$existing_sub_role = $this->Pegawai_model->get_sub_role($pegawai->uuid);

			if ($existing_sub_role === null) {
				$insert = $this->Pegawai_model->insert_role($uuid);
				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Sub Role berhasil di update');
				} else {
					$this->session->set_flashdata('error_msg', 'Data tidak ada yang di perbarui');
				}
			} else {
				$update = $this->Pegawai_model->update_role($uuid);
				if ($update) {
					$this->session->set_flashdata('success_msg', 'Sub Role berhasil di update');
				} else {
					$this->session->set_flashdata('error_msg', 'Data tidak ada yang di perbarui');
				}
			}

			redirect('pegawai');
		}

		$data = array(
			'data' => $this->Pegawai_model->get_by_uuid($uuid),
			'active_nav' => 'pegawai'
		);

		$this->load->view('partials/head.php', $data);
		$this->load->view('pegawai/sub-role.php', $data);
		$this->load->view('partials/footer.php');
	}


	public function detail($uuid)
	{
		$pegawai = $this->Pegawai_model->get_by_uuid($uuid);

		if ($pegawai->pendidikan == 1) {
			$pegawai->pendidikan = 'SMP';
		} else if ($pegawai->pendidikan == 2) {
			$pegawai->pendidikan = 'SMA/SMK';
		} else if ($pegawai->pendidikan == 3) {
			$pegawai->pendidikan = 'D3';
		} else if ($pegawai->pendidikan == 4) {
			$pegawai->pendidikan = 'D4/S1';
		} else if ($pegawai->pendidikan == 5) {
			$pegawai->pendidikan = 'S2';
		} else if ($pegawai->pendidikan == 6) {
			$pegawai->pendidikan = 'S3';
		}

		$data = array(
			// 'training' => $this->Pegawai_model->get_training($uuid),
			'pegawai' => $pegawai,
			'active_nav' => 'pegawai'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('pegawai/pegawai-detail', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid)
	{
		$user = $this->Pegawai_model->get_by_uuid($uuid);

		$username_rule = 'required';

		if ($this->input->post('username') != $user->username) {
			$username_rule .= '|is_unique[users.username]';
		}

		$rules = [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => $username_rule
			]
		];

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->Pegawai_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di update');
			} else {
				$this->session->set_flashdata('error_msg', 'Data tidak ada yang diperbarui');
			}

			redirect('pegawai');
		}

		$data = [
			'user' => $user,
			'dept' => $this->Departemen_model->get_all(),
			'active_nav' => 'pegawai',
			'title' => 'Pegawai | CPI Berbek'
		];

		$this->load->view('partials/head.php', $data);
		$this->load->view('pegawai/pegawai-edit', $data);
		$this->load->view('partials/footer.php');
	}

	// public function edit_nik($uuid)
	// {
	// 	$rules = [
	// 		[
	// 			'field' => 'nik',
	// 			'label' => 'NIK',
	// 			'rules' => 'required|is_unique[users.nik]'
	// 		]
	// 	];

	// 	$this->form_validation->set_rules($rules);

	// 	if($this->form_validation->run() == TRUE){
	// 		$insert = $this->Pegawai_model->update_nik($uuid);
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'NIK Pegawai berhasil di update');
	// 			redirect('pegawai');
	// 		} else {
	// 			$this->session->set_flashdata('error_msg', 'NIK Pegawai tidak ada yang di perbarui');
	// 			redirect('pegawai');
	// 		}
	// 	}

	// 	$data = array(
	// 		'user' => $this->Pegawai_model->get_by_uuid($uuid),
	// 		'active_nav' => 'pegawai',
	// 		'title' => 'Pegawai | CPI Berbek'
	// 	);

	// 	$this->load->view('partials/head.php', $data);
	// 	$this->load->view('pegawai/pegawai-edit-nik.php', $data);
	// 	$this->load->view('partials/footer.php');
	// }

	public function edit_password($uuid)
	{
		$rules = [
			[
				'field' => 'new-password',
				'label' => 'Password Baru',
				'rules' => 'required|min_length[5]'
			],
			[
				'field' => 'confirm-password',
				'label' => 'Konfirmasi Password',
				'rules' => 'required|matches[new-password]'
			]
		];

		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == TRUE){
			$insert = $this->Pegawai_model->update_password($uuid);
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Password Pegawai berhasil di update');
				redirect('home');
			} else {
				$this->session->set_flashdata('error_msg', 'Password Pegawai tidak ada yang di perbarui');
				redirect('home');
			}
		}

		$data = array(
			'user' => $this->Pegawai_model->get_by_uuid($uuid),
			'active_nav' => 'pegawai'
		);

		$this->load->view('partials/head.php', $data);
		$this->load->view('pegawai/pegawai-edit-password.php', $data);
		$this->load->view('partials/footer.php');
	}

	public function reset_password($uuid)
	{
		$update = $this->Pegawai_model->reset($uuid);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Password telah berhasil di reset');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Password gagal di reset');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function hapus_data($uuid)
	{
		$hapus = $this->Pegawai_model->hapus_data($uuid);
		if ($hapus) {
			$this->session->set_flashdata('success_msg', 'Data berhasil di Hapus');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Data gagal di Hapus');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function generate_username_ajax()
	{
		$fullname = $this->input->post('fullname');

		$username = $this->Pegawai_model->generate_username($fullname);

		echo json_encode([
			'username' => $username
		]);
	}

}
