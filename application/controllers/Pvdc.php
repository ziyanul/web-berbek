<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pvdc extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pvdc_model');
        $this->load->model('Counter_model');
        $this->load->library('form_validation');
        // $this->load->model('pdf_model');
        $this->load->model('User_model');
        if (!$this->auth_model->current_user()) {
            redirect('login');
        }
    }
    public function index()
    {
        $data = array(
            'data' => $this->Pvdc_model->get_pvdc_data(),
            'active_nav' => 'pvdc'
        );
        $this->load->view('partials/head-yield', $data);
        $this->load->view('pvdc/pvdc', $data);
        $this->load->view('partials/footer');
    }

    public function tambah()
    {
        $rules = $this->Pvdc_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Pvdc_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data PVDC berhasil di simpan.');
                redirect('pvdc');
            } else {
                redirect('pvdc');
                $this->session->set_flashdata('error_msg', 'Data PVDC gagal di simpan.');
            }
        }

        $data = array(
            'data'       => $this->Pvdc_model->get_all(),
            'active_nav' => 'pvdc'
        );


        $this->load->view('partials/head', $data);
        $this->load->view('pvdc/tambah', $data);
        $this->load->view('partials/footer');
    }


    public function edit($uuid)
    {
        $rules = $this->Pvdc_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            // Update header t_planning
            $update_planning = $this->Pvdc_model->update_planning($uuid);

            // Update detail PVDC
            $update = $this->Pvdc_model->update($uuid);

            if ($update_planning && $update) {

                $this->session->set_flashdata(
                    'success_msg',
                    'Data Planning berhasil diperbarui.'
                );
            } else {

                $this->session->set_flashdata(
                    'error_msg',
                    'Data Planning gagal diperbarui.'
                );
            }

            redirect('area');
        }

        $data = array(
            'planning'   => $this->Pvdc_model->get_planning_by_uuid($uuid),
            'data'       => $this->Pvdc_model->get_by_uuid($uuid),
            'uuid'       => $uuid,
            'active_nav' => 'pvdc'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('pvdc/form', $data);
        $this->load->view('partials/footer');
    }
}