<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use tcpdf\TCPDF;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class Tracking extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Tracking_model');
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(

			// 'data' => $this->Tracking_model->get_all(),
			// 'status' => $this->Tracking_model->getIssueDataWithLatestStatus(),
			'data' => $this->Tracking_model->get_coba(),
			'active_nav' => 'tracking'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('tracking/tracking', $data);
		$this->load->view('partials/footer');
	}

	public function tambah()
	{
		$rules = $this->Tracking_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$insert = $this->Tracking_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
				redirect('tracking');

			} else {
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
				redirect('tracking');
			}
		}


		$data = array(

			'active_nav' => 'tracking'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('tracking/tracking-tambah', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid)
	{
		$rules = $this->Tracking_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->Tracking_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
				redirect('tracking');

			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
				redirect('tracking');
			}
		}


		$data = array(
			'data' => $this->Tracking_model->get_by_uuid($uuid),
			'active_nav' => 'tracking'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('tracking/tracking-edit', $data);
		$this->load->view('partials/footer');
	}

	public function detail($uuid)
	{
		$data = array(
			'before' => $this->Tracking_model->get_before_by_issue_uuid($uuid),
			'detail' => $this->Tracking_model->get_detail_by_issue_uuid($uuid),
			'data' => $this->Tracking_model->get_by_uuid($uuid),
			'active_nav' => 'tracking'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('tracking/tracking-detail', $data);
		$this->load->view('partials/footer');

	}

	public function beforeafter($uuid)
	{
		$data = array(
			'after' 	=> $this->Tracking_model->get_after_by_issue_uuid($uuid),
			'before' 	=> $this->Tracking_model->get_before_by_issue_uuid($uuid),
			'data' 		=> $this->Tracking_model->get_by_uuid($uuid),
			'active_nav' => 'tracking'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('tracking/before-after', $data);
		$this->load->view('partials/footer');

	}

	public function afterhasil($uuid)
	{
		$data = array(
			'after' 	=> $this->Tracking_model->get_by_uuid($uuid),
			'data' 	=> $this->Tracking_model->get_last_hasil($uuid),

			'active_nav' => 'tracking'
		);
	
		$this->load->view('partials/head', $data);
		$this->load->view('tracking/after-hasil', $data);
		$this->load->view('partials/footer');

	}

	public function hasil($uuid)
	{
		$data = array(
			'after' 	=> $this->Tracking_model->get_after($uuid),
			'hasil' => $this->Tracking_model->get_hasil_by_after_uuid($uuid),
			'active_nav' => 'tracking'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('tracking/hasil', $data);
		$this->load->view('partials/footer');
	}

	public function tambahhasil($uuid)
	{

		$rules = [[
			'field' => 'evaluasi',
			'label' => 'Evaluasi',
			'rules' => 'required'
		],
		
		['field' => 'fdok_hasil',
		'label' => 'Dokumentasi',
		'rules' => 'callback_file_check_doc_hasil']];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$t = $this->Tracking_model->get_t_issue_uuid_by_after_uuid($uuid);

			
				$upload_path = FCPATH . "upload/";
				$config = [
					'upload_path'   => $upload_path,
            'allowed_types' => "pdf|doc|docx|jpeg|png", // Adjust allowed file types as needed
            'max_size'      => "2048", // 2MB
            'overwrite'     => TRUE,
            'encrypt_name'  => TRUE
        ];

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('fdok_hasil')) {

        	$error = $this->upload->display_errors();
        	$this->session->set_flashdata('error_msg', $error);
        } else {

        	$uploadData = $this->upload->data();
        	$file_name = $uploadData['file_name']; 

        	if ($this->Tracking_model->hasil($uuid, $file_name)) {
        		$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
        	} else {
        		$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
        	}
        }


    redirect('tracking/afterhasil/' . $t->t_issue_uuid);
}

$data = array (
	'data' => $this->Tracking_model->get_after_by_uuid($uuid),
	'hasil' => $this->Tracking_model->get_hasil_by_after_uuid($uuid),
	'active_nav' => 'tracking'
);
$this->load->view('partials/head', $data);
$this->load->view('tracking/tambah-hasil', $data);
$this->load->view('partials/footer');
}

public function tambahdetail($uuid)
{
	$rules = [
		['field' => 'fdetail',
		'label' => 'Detail',
		'rules' => 'required'],
		['field' => 'dokumentasi',
		'label' => 'Dokumentasi',
		'rules' => 'callback_file_check_dokumentasi']
	];
	$this->form_validation->set_rules($rules);

	if ($this->form_validation->run() === TRUE) {
        // Configuration for file upload
		$upload_path = FCPATH . "upload/";
		$config = [
			'upload_path'   => $upload_path,
            'allowed_types' => "pdf|doc|docx|jpeg|png", // Adjust allowed file types as needed
            'max_size'      => "2048", // 2MB
            'overwrite'     => TRUE,
            'encrypt_name'  => TRUE
        ];

        // Load upload library and initialize configuration
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('dokumentasi')) {

        	$error = $this->upload->display_errors();
        	$this->session->set_flashdata('error_msg', $error);
        } else {

        	$uploadData = $this->upload->data();
        	$file_name = $uploadData['file_name']; 

        	if ($this->Tracking_model->insertdetail($uuid, $file_name)) {
        		$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
        	} else {
        		$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
        	}
        }

        redirect('tracking/detail/' . $uuid);
    }
    $data = [
    	'data' => $this->Tracking_model->get_by_uuid($uuid),
    	'active_nav' => 'tracking'
    ];

    $this->load->view('partials/head', $data);
    $this->load->view('tracking/tambah-detail', $data);
    $this->load->view('partials/footer');
}

// Callback function to validate file type
public function file_check_dokumentasi($str)
{
	$allowed_mime_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
	$mime_type = $_FILES['dokumentasi']['type'];

	if (!in_array($mime_type, $allowed_mime_types)) {
		$this->form_validation->set_message('file_check_dokumentasi', 'File harus berformat PDF atau DOCX.');
		return false;
	}
	return true;
}

public function delete_detail($uuid) {

	$data = $this->db->get_where('t_detail', array('uuid' => $uuid ))->row();
	$this->Tracking_model->delete_detail($uuid);
	redirect('tracking/detail/' . $data->t_issue_uuid);
}

public function delete_before($uuid) {
	$data = $this->db->get_where('t_before', array('uuid' => $uuid ))->row();
	$this->Tracking_model->delete_before($uuid);
	redirect('tracking/detail/' .$data->t_issue_uuid);
}

public function delete_after($uuid) {

	$this->Tracking_model->delete_after($uuid);
	redirect('tracking/');
}

public function tambahbefore($uuid)
{
	$rules = [
		[
			'field' => 'fgap',
			'label' => 'GAP',
			'rules' => 'required'
		],
		['field' => 'fdok_before',
		'label' => 'Dokumentasi',
		'rules' => 'callback_file_check_doc_before']
	];

	$this->form_validation->set_rules($rules);

	if ($this->form_validation->run() === TRUE) {
        // Configuration for file upload
		$upload_path = FCPATH . "upload/";
		$config = [
			'upload_path'   => $upload_path,
            'allowed_types' => "pdf|doc|docx|jpeg|png", // Adjust allowed file types as needed
            'max_size'      => "2048", // 2MB
            'overwrite'     => TRUE,
            'encrypt_name'  => TRUE
        ];

        // Load upload library and initialize configuration
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('fdok_before')) {

        	$error = $this->upload->display_errors();
        	$this->session->set_flashdata('error_msg', $error);
        } else {

        	$uploadData = $this->upload->data();
        	$file_name = $uploadData['file_name']; 

        	if ($this->Tracking_model->insertbefore($uuid, $file_name)) {
        		$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
        	} else {
        		$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
        	}
        }

        redirect('tracking/detail/' . $uuid);
    }


    $data = array(
			// 'detail' => $this->Tracking_model->get_detail_by_issue_uuid($uuid),
    	'data' => $this->Tracking_model->get_by_uuid($uuid),
    	'active_nav' => 'tracking'
    );
    $this->load->view('partials/head', $data);
    $this->load->view('tracking/tambah-before', $data);
    $this->load->view('partials/footer');
}

public function tambahafter($uuid)
{
	$rules = [[
		'field' => 'fcap',
		'label' => 'CAP',
		'rules' => 'required'
	],
	['field' => 'fdok_after',
	'label' => 'Dokumentasi',
	'rules' => 'callback_file_check_doc_after']];
	$this->form_validation->set_rules($rules);

	if ($this->form_validation->run() === TRUE) {
        // Configuration for file upload
		$upload_path = FCPATH . "upload/";
		$config = [
			'upload_path'   => $upload_path,
            'allowed_types' => "pdf|doc|docx|jpeg|png", // Adjust allowed file types as needed
            'max_size'      => "2048", // 2MB
            'overwrite'     => TRUE,
            'encrypt_name'  => TRUE
        ];

        // Load upload library and initialize configuration
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('fdok_after')) {

        	$error = $this->upload->display_errors();
        	$this->session->set_flashdata('error_msg', $error);
        } else {
        	
        	$uploadData = $this->upload->data();
        	$file_name = $uploadData['file_name']; 

        	if ($this->Tracking_model->insertafter($uuid, $file_name)) {
        		$this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
        	} else {
        		$this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
        	}
        }
        $d = $this->Tracking_model->get_by_uuid($uuid);
        redirect('tracking/beforeafter/' . $d->uuid);
    }


    $data = array(
			// 'detail' => $this->Tracking_model->get_detail_by_issue_uuid($uuid),
    	'data' => $this->Tracking_model->get_by_uuid($uuid),
    	'active_nav' => 'tracking'
    );
    $this->load->view('partials/head', $data);
    $this->load->view('tracking/tambah-after', $data);
    $this->load->view('partials/footer');
}

public function file_check_doc_before($str)
{
	$allowed_mime_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
	$mime_type = $_FILES['fdok_before']['type'];

	if (!in_array($mime_type, $allowed_mime_types)) {
		$this->form_validation->set_message('file_check_doc_before', 'File harus berformat PDF, JPEG atau PNG');
		return false;
	}

	return true;
}

public function file_check_doc_after($str)
{
	$allowed_mime_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword'];
	$mime_type = $_FILES['fdok_after']['type'];

	if (!in_array($mime_type, $allowed_mime_types)) {
		$this->form_validation->set_message('file_check_doc_after', 'File harus berformat PDF, JPEG atau PNG');
		return false;
	}

	return true;
}

public function file_check_doc_hasil($str)
{
	$allowed_mime_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword'];
	$mime_type = $_FILES['fdok_hasil']['type'];

	if (!in_array($mime_type, $allowed_mime_types)) {
		$this->form_validation->set_message('file_check_doc_hasil', 'File harus berformat PDF, JPEG atau PNG');
		return false;
	}

	return true;
}

}