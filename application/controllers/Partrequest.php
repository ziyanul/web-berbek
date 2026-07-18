<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class Partrequest extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Part_model');
		$this->load->model('Partrequest_model');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
		$this->load->library('spreadsheet_lib');
		$this->load->model('Auth_model');
		$this->load->library('upload');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Partrequest_model->get_pengajuan(),
			'active_nav' => 'pengajuan-part'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part-request/pengajuan', $data);
		$this->load->view('partials/footer');
	}

	public function history()
	{
		$data = array(
			'data' => $this->Partrequest_model->get_history(),
			'active_nav' => 'pengajuan-history'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part-request/pengajuan-history', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Partrequest_model->rules1();
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$config = array(
				'image_library' => 'gd2',
				'upload_path' => './upload/',
				'allowed_types' => 'jpg|png|jpeg|pdf',
				'overwrite' => TRUE,
				'encrypt_name' => TRUE
			);

			$this->upload->initialize($config);

			if (!$this->upload->do_upload('foto')) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error_msg', $error);
			} else {
				$data = $this->upload->data();
				if ($data['is_image']) {
					$resize_config = array(
						'image_library' => 'gd2',
						'source_image' => $data['full_path'],
						'maintain_ratio' => TRUE,
						'width' => 500,
						'height' => 500
					);

					$this->image_lib->initialize($resize_config);

					if (!$this->image_lib->resize()) {
						$error = $this->image_lib->display_errors();
						$this->session->set_flashdata('error_msg', $error);
					}
					$this->image_lib->clear();
				}
				$foto = $data['file_name'];
				$insert = $this->Partrequest_model->insert_pengajuan($foto);
				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Data SparePart berhasil di simpan.');
					redirect('partrequest');
				} else {
					$this->session->set_flashdata('error_msg', $this->session->flashdata('error_msg'));
					redirect('partrequest');
				}
			}
		}

		$data = array(
			'area' => $this->Area_model->get_all(),
			'active_nav' => 'pengajuan-part'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part-request/pengajuan-tambah', $data);
		$this->load->view('partials/footer');
	}



	public function file_check_foto($str)
	{
		$allowed_mime_types = ['image/jpeg', 'image/png'];
		$mime_type = $_FILES['foto']['type'];
		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check_foto', 'File harus berformat JPEG atau PNG');
			return false;
		}
		return true;
	}

	public function detail($uuid)
	{
		$data = array(
			'data' => $this->Partrequest_model->get_pengajuan_uuid($uuid),
			'status' => $this->Partrequest_model->get_status_pengajuan_data($uuid),
			'approval' => $this->Partrequest_model->get_approval_status($uuid),
			'foto' => $this->Partrequest_model->get_foto_pengajuan($uuid),
			'active_nav' => ($this->uri->segment(2)=='history'?'pengajuan-history':'pengajuan-part')
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part-request/detail-pengajuan', $data);
		$this->load->view('partials/footer');
	}

	public function tambahfoto($uuid)
	{
		$rules_foto = [[
			'field' => 'dokumen',
			'label' => 'Foto',
			'rules' => 'callback_file_check_dokumen'
		]];

		
		$this->form_validation->set_rules($rules_foto);
		if ($this->form_validation->run() === TRUE) {
			$config = array(
				'image_library' => 'gd2',
				'upload_path' => './upload/',
				'allowed_types' => 'jpg|png|jpeg|pdf',
				'overwrite' => TRUE,
				'encrypt_name' => TRUE
			);

			$this->upload->initialize($config);

			if (!$this->upload->do_upload('dokumen')) {
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error_msg', $error);
			} else {
				$data = $this->upload->data();
				if ($data['is_image']) {
					$resize_config = array(
						'image_library' => 'gd2',
						'source_image' => $data['full_path'],
						'maintain_ratio' => TRUE,
						'width' => 500,
						'height' => 500
					);

					$this->image_lib->initialize($resize_config);

					if (!$this->image_lib->resize()) {
						$error = $this->image_lib->display_errors();
						$this->session->set_flashdata('error_msg', $error);
					}
					$this->image_lib->clear();
				}
				$dokumen = $data['file_name'];
				$insert = $this->Partrequest_model->insert_dokumen($dokumen, $uuid);
				if ($insert) {
					$this->session->set_flashdata('success_msg', 'Data SparePart berhasil di simpan.');
					redirect('partrequest');
				} else {
					$this->session->set_flashdata('error_msg', $this->session->flashdata('error_msg'));
					redirect('partrequest');
				}
			}
		}

		$data = array(
			'data' => $this->Partrequest_model->get_pengajuan_uuid($uuid),
			'foto' => $this->Partrequest_model->get_foto_pengajuan($uuid),
			'active_nav' => 'pengajuan-part'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part-request/tambah-foto', $data);
		$this->load->view('partials/footer');
	}

	public function file_check_dokumen($str)
	{
		$allowed_mime_types = ['image/jpeg', 'image/png'];
		$mime_type = $_FILES['dokumen']['type'];
		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check_dokumen', 'File harus berformat JPEG atau PNG');
			return false;
		}
		return true;
	}

	public function mengetahui($uuid)
	{
		$update = $this->Monitor_model->update_mengetahui($uuid);
		if ($update) {
			$this->session->set_flashdata('success_msg', 'Paraf Berhasil di Tambahkan');
			echo json_encode(array('status' => TRUE));
		} else {
			$this->session->set_flashdata('error_msg', 'Paraf Gagal di Tambahkan');
			echo json_encode(array('status' => FALSE));
		}
	}

	public function status_part($uuid)
	{
		$rules = [
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$insertStatus = $this->Partrequest_model->insert_status_part($uuid);
			if ($insertStatus) {
				$this->session->set_flashdata('success_msg', 'status berhasil di Update');
				redirect('partrequest');
			} else {
				$this->session->set_flashdata('error_msg', 'Invalid status value');
				redirect('partrequest');
			}
		}

		$data = array (
			'data' => $this->Partrequest_model->get_pengajuan_uuid($uuid),
			'status' => $this->Partrequest_model->get_status_pengajuan_data($uuid),
			'active_nav' => 'pengajuan-part'
		);
		
		
		$this->load->view('partials/head', $data);
		$this->load->view('part-request/pengajuan-status', $data);
		$this->load->view('partials/footer');
	}

}