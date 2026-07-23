<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auth_model');
		// $this->load->model('Home_model');
		$this->load->model('Pm_model');
		$this->load->model('Gmp_model');
		$this->load->model('Am_model');
		$this->load->model('Monitor_model');
		$this->load->model('Filler_model');
        $this->load->model('Partrequest_model');

		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		
		$selected_uuid = $this->input->get('uuid');
    if (empty($selected_uuid)) {
        $selected_uuid = $this->Filler_model->get_last_plan_uuid();
    }

    $performa_data = array(
        'target' => $this->Filler_model->get_counter_by_t_planning_uuid($selected_uuid),
        'plan' => $this->Filler_model->get_plan_uuid($selected_uuid),
        'uuids' => $this->Filler_model->get_all_uuids()
    );

    $data = array(
        'maintenance' => $this->Pm_model->count_maintenance(),
        'monitor' => $this->Monitor_model->get_monitor_count(),
        'pengajuan' => $this->Partrequest_model->get_total_pengajuan(),
        'gmp' => $this->Gmp_model->get_total_gmp(),
        'auto' => $this->Am_model->count_am(),
        'active_nav' => 'home',
        'performa_data' => $performa_data
    );

   
    $this->load->view('partials/head', $data);
    $this->load->view('home/home', $data);
    $this->load->view('partials/footer');
}

public function get_performa_by_uuid()
{
    $uuid = $this->input->get('uuid');

    if (!$uuid) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([]));
    }

    $data = $this->Filler_model->get_counter_by_t_planning_uuid($uuid);

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

}