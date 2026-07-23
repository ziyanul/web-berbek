<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Rj_filler extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->load->model('Mesin_model');
        $this->load->model('Varian_model');
        $this->load->model('Rj_filler_model');
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
        $this->config->load('relasi_uuid');
        $this->filler = $this->config->item('filler_uuid');

        if(!$this->Auth_model->current_user()){
            redirect('login');
        }
    }

    public function index()
    {
        $rules = $this->Rj_filler_model->rules_operator();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Rj_filler_model->insert_operator();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di tambah.');
                redirect('rj_filler/');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di tambah.');
                redirect('rj_filler/');
            }
        }

        $data = array(
            'data' => $this->Rj_filler_model->get_data(),
            'total' => $this->Rj_filler_model->get_total_berat_mesin(),
            'mesin' => $this->Rj_filler_model->get_mesin_filler(),
            'operator' => $this->Rj_filler_model->get_operator()
        );

        $this->load->view('partials/head-view');
        $this->load->view('rj-filler/home', $data);
        $this->load->view('partials/footer');
    }

    public function mesin()
    {
        $rules = $this->Rj_filler_model->rules_mesin();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Rj_filler_model->insert_mesin();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di tambah.');
                redirect('rj_filler/mesin');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di tambah.');
                redirect('rj_filler/mesin');
            }
        }

        $data = array(
            'data' => $this->Rj_filler_model->get_data_mesin(),
            'total' => $this->Rj_filler_model->get_total_berat_mesin(),
            'mesin' => $this->Rj_filler_model->get_mesin_filler()

            // 'active_nav' => 'rj_filler'
        );
        // echo "<pre>";
        // print_r ($data['total']);
        // echo "</pre>";
        $this->load->view('partials/head-view', $data);
        $this->load->view('rj-filler/mesin', $data);
        $this->load->view('partials/footer');
    }

    public function operator()
    {
        $rules = $this->Rj_filler_model->rules_operator();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Rj_filler_model->insert_operator();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di tambah.');
                redirect('rj_filler/operator');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di tambah.');
                redirect('rj_filler/operator');
            }
        }

        $data = array(
            'data' => $this->Rj_filler_model->get_data_operator(),
            'total' => $this->Rj_filler_model->get_total_berat_operator(),
            'mesin' => $this->Rj_filler_model->get_mesin_filler(),
            'operator' => $this->Rj_filler_model->get_operator()
            // 'active_nav' => 'rj_filler'
        );
        // echo "<pre>";
        // print_r ($data['data']);
        // echo "</pre>";
        $this->load->view('partials/head-view', $data);
        $this->load->view('rj-filler/operator', $data);
        $this->load->view('partials/footer');
    }



    public function tambah()
    {
        $rules = $this->Rj_filler_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {

            $insert = $this->Rj_filler_model->insert();

            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di tambah.');
                redirect('rj_filler');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di tambah.');
                redirect('rj_filler');
            }
        }

        $data = array(
            'varian' => $this->Varian_model->get_all(),
            'plan' => $this->Rj_filler_model->get_planning(),
            'mesin' => $this->Rj_filler_model->get_mesin_filler(),
            'operator' => $this->Rj_filler_model->get_operator(),
            'active_nav' => 'rj_filler'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('rj-filler/tambah', $data);
        $this->load->view('partials/footer');
    }

    public function editrjmesin()
    {
        $uuid = $this->input->post('uuid');
        $rules = [
            [
                'field' => 'berat',
                'label' => 'Berat',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Rj_filler_model->update_rjmesin($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('rj_filler/mesin');
            } else {
                redirect('rj_filler/mesin');
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
            }
        }

    }

    public function editrjopr()
    {
        $uuid = $this->input->post('uuid');
        $rules = [
            [
                'field' => 'berat',
                'label' => 'Berat',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Rj_filler_model->update_rjopr($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('rj_filler/');
            } else {
                redirect('rj_filler/');
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
            }
        }

    }


    public function get_kode_batch($varian_uuid = null)
    {
        if (!$varian_uuid) {
            echo json_encode([]);
            return;
        }

        $data = $this->Rj_filler_model->get_kode_batch($varian_uuid);
        echo json_encode($data);
    }

    public function get_data_by_uuid()
    {
        $uuid = $this->input->post('uuid');
        $data = $this->Rj_filler_model->get_by_uuid($uuid);
        echo json_encode($data);
    }

    public function hapus_rjmesin($uuid)
    {
        $update = $this->Rj_filler_model->delete_rjmesin($uuid);

        if ($update) {
            $this->session->set_flashdata('success_msg', 'Data Berhasil di Hapus');
            echo json_encode(array('status' => TRUE));
        } else {
            $this->session->set_flashdata('error_msg', 'Data Berhasil di Hapus');
            echo json_encode(array('status' => FALSE));
        }
    }



}