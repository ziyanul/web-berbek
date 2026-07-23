<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Part extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Area_model');
		$this->load->model('Mesin_model');
		$this->load->model('Part_model');
		$this->load->model('Monitor_model');
		$this->load->library('form_validation');
		$this->load->model('Auth_model');

		if(!$this->Auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'area' => $this->Area_model->get_all(),
			'data' => $this->Part_model->get_all(),
			'active_nav' => 'sparepart'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part/part', $data);
		$this->load->view('partials/footer');
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('part')->result();

	}

	public function tambah()
	{
		$rules = $this->Part_model->rules();
		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() === TRUE) {
			
			$insert = $this->Part_model->insert();
			if ($insert) {
				$part_id = $this->db->insert_id();
				$insert_history = $this->Part_model->insert_part_history($part_id);
				if ($insert_history) {
					$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
					redirect('part');
				}
				
			} else {
				redirect('part');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}

		$data = array(
			'area' => $this->Area_model->get_all(),
			'part' => $this->Part_model->get_all(),
			'mesin' => $this->Mesin_model->get_all(),
			'active_nav' => 'sparepart'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part/part-tambah', $data);
		$this->load->view('partials/footer');
	}

	public function edit($uuid)
	{
		
		$rules = [
			
			[
				'field' => 'lifetime',
				'label' => 'lifetime',
				'rules' => 'required'
			],
			[
				'field' => 'harga',
				'label' => 'harga',
				'rules' => 'required'
			],
			
			[
				'field' => 'kondisi',
				'label' => 'kondisi',
				'rules' => 'required'
			]
		];
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) { 
			
			$update = $this->Part_model->update($uuid); 
			if ($update) { 

				$insert_history = $this->Part_model->update_part_history($uuid);
				if ($insert_history) {
					$this->session->set_flashdata('success_msg', 'Data Mesin berhasil di simpan.');
					
					redirect('part');
				}
			} else { // Jika update sama dg false
				redirect('part');
				$this->session->set_flashdata('error_msg', 'Data Area gagal di simpan.');
			}
		}

		$data = array(
			// 'area' => $this->Area_model->get_all(),
			// 'mesin' => $this->Part_model->get_mesin_name($uuid),
			'data' => $this->Part_model->get_by_uuid($uuid),
			'active_nav' => 'sparepart'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part/part-edit', $data);
		$this->load->view('partials/footer');
	}

	public function history($uuid)
	{
		$data = array(
			'data' => $this->Part_model->get_all_history($uuid),
			'active_nav' => 'sparepart'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('part/history', $data);
		$this->load->view('partials/footer');

	}

	public function get_part_name($uuid)
	{
		$part = $this->Part_model->get_part_name($uuid);
		print_r(json_encode($part));
	}

	public function get_mesin_by_area($area_uuid)
	{
		$data = $this->Mesin_model->get_by_area($area_uuid);
		print_r(json_encode($data));
	}

	public function ajax()
	{
		$list = $this->Part_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->nama_mesin;
                $row[] = $field->nama_part;
                $row[] = $field->lifetime;
                $row[] = $field->harga;
                $row[] = $field->actions;

                $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Part_model->count_all(),
                "recordsFiltered" => $this->Part_model->count_filtered(),
                "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

}