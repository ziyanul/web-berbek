<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gmp extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Gmp_model');
		$this->load->model('Chemical_model');
		$this->load->model('Sanitasi_model');
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
			'data' => $this->Gmp_model->get_gmp_data(),
			'active_nav' => 'gmp'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp', $data);
		$this->load->view('partials/footer');
	}
	public function history()
	{
		
		$data = array(
			'data' => $this->Gmp_model->get_history(),
			'active_nav' => 'gmp-history'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-history', $data);
		$this->load->view('partials/footer');
	}
	public function tpm()
	{
		
		$data = array(
			'data' => $this->Gmp_model->get_tpm(),
			'active_nav' => 'gmp-tpm'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-tpm', $data);
		$this->load->view('partials/footer');
	}
	public function tambah()
	{
		$rules = $this->Gmp_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			
			$insert = $this->Gmp_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('gmp/tpm');
				} else {
					redirect('gmp');
				}	
			} else {
				redirect('gmp');
				$this->session->set_flashdata('error_msg', $this->session->flashdata('error_msg'));
				if ($this->uri->segment(2)=='tpm') {
					redirect('gmp/tpm');
				} else {
					redirect('gmp');
				}
			}
		}
		$data = array(
			'area' => $this->Gmp_model->get_area(),
			'lokasi' => $this->Gmp_model->get_lokasi(),
			'kegiatan' => $this->Gmp_model->get_kegiatan(),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'gmp-tpm':'gmp')
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-tambah', $data);
		$this->load->view('partials/footer');
	}
	
	public function tambahlokasi()
	{
		$rules = [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'lokasi',
				'label' => 'SubArea',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			
			$insert = $this->Gmp_model->insertlokasi();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('gmp/area');
				
			} else {
				redirect('gmp/area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'area' => $this->Gmp_model->get_area(),
			'active_nav' => 'gmp-area'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/tambah-lokasi', $data);
		$this->load->view('partials/footer');
	}
	public function tambahkegiatan()
	{
		$rules = [
			[
				'field' => 'kegiatan',
				'label' => 'Kegiatan',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			
			$insert = $this->Gmp_model->insertkegiatan();
			if ($insert) {
				$gmp_id = $this->db->insert_id();
				$insert_kondisi = $this->Gmp_model->insert_kondisi($gmp_id);
				if ($insert_kondisi) {
					$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
					redirect('gmp/data');
				}
			} else {
				redirect('gmp/data');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'area' 		=> $this->Gmp_model->get_area(),
			'lokasi' 	=> $this->Gmp_model->get_lokasi(),
			'persen'	=> $this->Chemical_model->get_persen(),
			'kondisi'	=> $this->Sanitasi_model->get_kondisi(),
			'tindakan'	=> $this->Sanitasi_model->get_tindakan(),
			'active_nav' => 'gmp-area'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/tambah-kegiatan', $data);
		$this->load->view('partials/footer');
	}
	public function edit($uuid)
	{
		$rules = [
			[
				'field' => 'jadwal',
				'label' => 'Jadwal',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$config = array(
				'upload_path' => "./upload/",
				'allowed_types' => "jpg|png|jpeg|pdf",
				'overwrite' => TRUE,
				'max_size' => "2048",
				'encrypt_name' => TRUE
			);
			$this->upload->initialize($config);
			$dok_after = '';
			if (!$this->upload->do_upload('dokumentasi_after')) {
				$error = $this->upload->display_errors();
			} else {
				$data = $this->upload->data();
				$dok_after = $data['file_name'];
			}
			$update = $this->Gmp_model->update($uuid, $dok_after); 
			if ($update) { // Jika update sama dg true
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('gmp/tpm');
				} else {
					redirect('gmp');
				}	
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
				if ($this->uri->segment(2)=='tpm') {
					redirect('gmp/tpm');
				} else {
					redirect('gmp');
				}	
			}
		}
		$data = array(
			'data' => $this->Gmp_model->get_detail($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'gmp-tpm':'gmp')
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-edit', $data);
		$this->load->view('partials/footer');	
	}
	public function editarea($uuid)
	{
		$rules = [
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$update = $this->Gmp_model->updatearea($uuid); 
			if ($update) { 
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('gmp/area');
			} else { 
				redirect('gmp/area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'area' => $this->Gmp_model->get_area_name($uuid),
			'active_nav' => 'gmp-area'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/edit-area', $data);
		$this->load->view('partials/footer');
	}
	public function editlokasi($uuid) 
	{
		$rules = [
			[
				'field' => 'lokasi',
				'label' => 'Lokasi',
				'rules' => 'required'
			],
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) { 
			$update = $this->Gmp_model->updatelokasi($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('gmp/area');
			} else {
				redirect('gmp/area');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'lokasi' => $this->Gmp_model->lokasi_by_area($uuid),
			'active_nav' => 'gmp-area'
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/edit-lokasi', $data);
		$this->load->view('partials/footer');
	}
	public function editkegiatan($uuid) 
	{
		$rules = [
			[
				'field' => 'kegiatan',
				'label' => 'Kegiatan',
				'rules' => 'required'
			],
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) { 
			$update = $this->Gmp_model->updatekegiatan($uuid);
			if ($update) {
			$insert_target = $this->Gmp_model->update_target($uuid); 
				$this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
				redirect('gmp/data');
			} else { 
				redirect('gmp/data');
				$this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
			}
		}
		$data = array(
			'persen'	=> $this->Chemical_model->get_persen(),
			'kegiatan' => $this->Gmp_model->get_kegiatan_name($uuid),
			'active_nav' => 'gmp-data'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/edit-kegiatan', $data);
		$this->load->view('partials/footer');
	}
	public function status($uuid)
	{
		$rules = [
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Catatan',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() === TRUE) {
			$status 	= $this->input->post('status');
			
			if ($status == 1) {
				$insertnew = $this->Gmp_model->insertnew($uuid);
				$insertstatus = $this->Gmp_model->status($uuid);
				if ($insertstatus && $insertnew) {
					$this->session->set_flashdata('success_msg', 'Pengajuan berhasil di ACC');
					redirect(($this->uri->segment(2) == 'tpm' ? 'gmp/tpm' : 'gmp'));
				}
			} elseif ($status == 2) {
				$insertstatus = $this->Gmp_model->status($uuid);
				if ($insertstatus) {
					$this->session->set_flashdata('success_msg', 'Pengajuan tidak di ACC berhasil');
					redirect(($this->uri->segment(2) == 'tpm' ? 'gmp/tpm' : 'gmp'));
				}
			} else {
				$this->session->set_flashdata('error_msg', 'Invalid status value');
			}
		}
		$data = array (
			'data' => $this->Gmp_model->get_by_uuid($uuid),
			'status' => $this->Gmp_model->get_status_by_gmp_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'gmp-tpm':'gmp')
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-status', $data);
		$this->load->view('partials/footer');
	}
	public function data()
	{
		$data = array(
			'kegiatan' => $this->Gmp_model->get_kegiatan(),
			'active_nav' => 'gmp-data'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data', $data);
		$this->load->view('partials/footer');
	}
	
	public function lokasi($uuid)
	{
		$data = array(
			'lokasi' => $this->Gmp_model->get_all_lokasi($uuid),
			'area' => $this->Gmp_model->get_area_name($uuid),
			'active_nav' => 'gmp-area'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/lokasi', $data);
		$this->load->view('partials/footer');
	}
	public function kegiatan($uuid)
	{
		$data = array(
			'kegiatan' => $this->Gmp_model->get_all_kegiatan($uuid),
			'lokasi' => $this->Gmp_model->lokasi_by_area($uuid),
			'area' => $this->Gmp_model->get_area_name($uuid),
			'active_nav' => 'gmp-data'
		);
		
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/data/kegiatan', $data);
		$this->load->view('partials/footer');
	}
	public function detail($uuid)
	{
		$data = array(
			
			'data' => $this->Gmp_model->get_detail($uuid),
			'status' => $this->Gmp_model->get_status_by_gmp_uuid($uuid),
			'active_nav' => ($this->uri->segment(2)=='tpm'?'gmp-tpm':($this->uri->segment(2)=='history'?'gmp-history':'gmp')),
		);
		$this->load->view('partials/head', $data);
		$this->load->view('gmp/gmp-detail', $data);
		$this->load->view('partials/footer');
	}
	public function get_kegiatan_by_lokasi($lokasi_uuid)
	{
		$data = $this->Gmp_model->get_kegiatan_by_lokasi($lokasi_uuid);
		print_r(json_encode($data));
	}
	public function get_kegiatan_name($uuid)
	{
		$data = $this->Gmp_model->get_kegiatan_name($uuid);
		print_r(json_encode($data));
	}
	public function get_lokasi_by_area($area_uuid)
	{
		$data = $this->Gmp_model->get_lokasi_by_area($area_uuid);
		print_r(json_encode($data));
	}

public function get_tindakan_data()
	{
		$data = $this->Sanitasi_model->get_tindakan();
		print_r(json_encode($data));
	}

	public function get_kondisi_data()
	{
		$data1 = $this->Sanitasi_model->get_kondisi();
		print_r(json_encode($data1));
	}

	public function delete_kegiatan($uuid) 
	{
		$this->Gmp_model->delete_kegiatan($uuid);
		redirect('gmp/data/');
	}
	public function delete_gmp($uuid) 
	{
		$this->Gmp_model->delete_gmp($uuid);
		redirect($this->uri->segment(2) == 'tpm' ? 'gmp/tpm' : 'gmp');
	}
}