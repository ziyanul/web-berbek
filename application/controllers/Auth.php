<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Auth_model');
		$this->load->library('form_validation');

	}

	public function index()
	{
	}

	public function login()
	{

		if($this->Auth_model->current_user()){
			redirect(base_url('portal'));
		}
		
		$rules = $this->Auth_model->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			return $this->load->view('auth/login');
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if($this->Auth_model->login($username, $password)){
			redirect(base_url('portal'));
		} else {
			$this->session->set_flashdata('error_msg', 'Login Gagal, pastikan username dan passwrod benar!');
		}

		$this->load->view('auth/login');
		
	}


	public function logout()
	{
		$this->Auth_model->logout();
		redirect(base_url());
	}
}
