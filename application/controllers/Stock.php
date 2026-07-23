<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('area_model');
		$this->load->model('stock_model');
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->model('auth_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'data' => $this->stock_model->get_all(),
			'area' => $this->area_model->get_all(),
			'active_nav' => 'stock'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('stock/data', $data);
		$this->load->view('partials/footer');
	}

	public function detail_mp($item_uuid)
	{
		$data = array(
			'data' => $this->stock_model->get_detail_mp($item_uuid),
			'active_nav' => 'stock'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('stock/detail-mp', $data);
		$this->load->view('partials/footer');
	}

	public function get_filtered_data()
	{
		$area_uuid = $this->input->post('area_uuid');
		$data = $this->stock_model->get_area_data($area_uuid);
		echo json_encode($data);
	}
}