<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuci extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Drystore_model');
        $this->load->library('form_validation');
    }

    public function packing()
    {
        $data = array(
           'data' => $this->Drystore_model->get_all(),

            'active_nav' => 'drystore'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('drystore/drystore', $data);
        $this->load->view('partials/footer');
    }

    public function type()
    {
        $data = array(
           'data' => $this->Drystore_model->get_all(),

            'active_nav' => 'drystore'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('drystore/drystore', $data);
        $this->load->view('partials/footer');
    }


}