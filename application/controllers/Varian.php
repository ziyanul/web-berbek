<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Varian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Varian_model');
        $this->load->library('form_validation');
        $this->load->model('Auth_model');

        if (!$this->Auth_model->current_user()) {
            redirect('login');
        }
    }

    public function index()
    {
        $data = array(
            'data' => $this->Varian_model->get_all(),

            'active_nav' => 'varian'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('varian/varian', $data);
        $this->load->view('partials/footer');
    }
    public function simpan()
    {
        $rules = $this->Varian_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Varian_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data varian berhasil di simpan.');
                redirect('Varian');
            } else {
                redirect('Varian');
                $this->session->set_flashdata('error_msg', 'Data varian gagal di simpan.');
            }
        }
    }

    public function edit($uuid)
    {
        $rules = $this->Varian_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Varian_model->update($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data varian berhasil di ubah.');
                redirect('Varian');
            } else {
                redirect('Varian');
                $this->session->set_flashdata('error_msg', 'Data varian gagal di ubah.');
            }
        }

        $data = array(
            'data' => $this->Varian_model->get_by_uuid($uuid),

            'active_nav' => 'varian'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('varian/edit', $data);
        $this->load->view('partials/footer');
    }
}
