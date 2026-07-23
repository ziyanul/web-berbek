<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartustock extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->model('kartustock_model');
		$this->load->model('mesin_model');
		$this->load->library('form_validation');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

    public function index ()
    {
		$data = array(
			'data' => $this->kartustock_model->get_urgent(),
			'active_nav' => 'kartustock'
		);

		$this->load->view('partials/head', $data);
		$this->load->view('kartu-stock/kartu-stock', $data);
		$this->load->view('partials/footer');
	}

}
