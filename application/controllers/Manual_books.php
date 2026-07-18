<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manual_books extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->model('Area_model');
		$this->load->model('Am_model');
		$this->load->model('Manual_books_model');
		$this->load->model('Mesin_model');
		$this->load->library('form_validation');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->Manual_books_model->get_all(),
			'area' => $this->Area_model->get_all(),
			'active_nav' => 'manual_books'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('manual_books/manual_books', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = [
			[
				'field' => 'judul',
				'label' => 'Judul',
				'rules' => 'required',
				'errors' => [
					'required' => 'Judul tidak boleh kosong !'
				]
			],
			['field' => 'dokumentasi',
			'label' => 'Dokumentasi',
			'rules' => 'callback_file_check_pdf_manual']
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$upload_path = FCPATH . "upload/";
			$config = [
				'upload_path'   => $upload_path,
				'allowed_types' => "pdf",
				'max_size'      => "2048",
				'overwrite'     => TRUE,
				'encrypt_name'  => TRUE
			];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('pdf')) {

				$error = $this->upload->display_errors();
				$this->session->set_flashdata('error_msg', $error);
			} else {

				$uploadData = $this->upload->data();
				$file_name = $uploadData['file_name']; 

				if ($this->Manual_books_model->insert_data($file_name)) {
					$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
				} else {
					$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
				}
			}

			redirect('manual_books/');
		}

		$data = array(
			'area' =>  $this->Manual_books_model->get_area(),
			'mesin' => $this->Manual_books_model->get_mesin(),
			'active_nav' => 'manual_books'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('manual_books/tambah', $data);
		$this->load->view('partials/footer');
	}

	public function file_check_pdf_manual($str)
	{
		$allowed_mime_types = ['application/pdf'];
		$mime_type = $_FILES['pdf']['type'];

		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check_pdf_manual', 'File harus berformat PDF!');
			return false;
		}
		return true;
	}

	public function get_mesin_by_area($area_uuid)
	{
		$data = $this->Manual_books_model->get_mesin_by_area($area_uuid);

		print_r(json_encode($data));
	}

    
}