<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bahan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Bahan_model');     
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
           'data' => $this->Bahan_model->get_all(),

            'active_nav' => 'bahan'
        );
        
        $this->load->view('partials/head-yield', $data);
        $this->load->view('bahan/bahan', $data);
        $this->load->view('partials/footer');
    }
    public function simpan()
    {
      $rules = $this->Bahan_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {
    
            $insert = $this->Bahan_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Master Bahan berhasil di simpan.');
                redirect('Bahan');
            } else {
                redirect('Bahan');
                $this->session->set_flashdata('error_msg', 'Data Master Bahan gagal di simpan.');
            }
        }
    }

    public function edit($uuid)
    {
      $rules = $this->Bahan_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {
    
            $update = $this->Bahan_model->update($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
                redirect('Bahan');
            } else {
                redirect('Bahan');
                $this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
            }
        }

        $data = array(
           'data' => $this->Bahan_model->get_by_uuid($uuid),

            'active_nav' => 'bahan'
        );
        
        $this->load->view('partials/head-yield', $data);
        $this->load->view('bahan/edit', $data);
        $this->load->view('partials/footer');
    }

    public function hapus($uuid)
{
    $delete = $this->Bahan_model->delete($uuid);

    if ($delete) {
        $this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error_msg', 'Data gagal dihapus.');
    }

    redirect('Bahan');
}
}