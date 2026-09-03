<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drystore extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Drystore_model');
        $this->load->library('form_validation');
        $this->load->model('Auth_model');
			if(!$this->Auth_model->current_user()){
				redirect('login');
			}
    }

    public function index()
    {
        $data['title'] = 'Drystore';
        $data['active_nav'] = 'Drystore';
        $data['data'] = $this->Drystore_model->get_all();

        $this->load->view('partials/head-packing', $data);
        $this->load->view('drystore/drystore', $data);
        $this->load->view('partials/footer');
    }

    public function dashboard()
    {
        $data['title'] = 'Drystore';
        $data['active_nav'] = 'Drystore';

        $this->load->view('dashboard/dashboard-packing', $data);
    }

    /**
     * Tambah transaksi hari ini
     */
    public function tambah()
    {
        // Tanggal dari server
        $tanggal = date('Y-m-d');

        $data['title'] = 'Tambah Drystore';

        $data['tanggal'] = $tanggal;

        $data['types'] =
            $this->Drystore_model->get_all_type();

        $data['wastes'] =
            $this->Drystore_model->get_all_waste();

            $data['active_nav'] = 'Drystore';

        $this->load->view('partials/head-packing', $data);
        $this->load->view('drystore/tambah', $data);
        $this->load->view('partials/footer');
    }

    /**
     * Simpan transaksi baru
     */
    public function simpan()
    {
        if ($this->input->method() !== 'post') {
            redirect('drystore');
            return;
        }

        // Tetap menggunakan server date
        $tanggal = $this->input->post('tanggal');

        /*
         * Ambil user UUID.
         *
         * SESUAIKAN bagian ini dengan session
         * user_uuid yang digunakan project-mu.
         */
        $user_uuid = $this->session->userdata('user_uuid');

        $result =
            $this->Drystore_model->insert_harian(
                $tanggal,
                $this->input->post(),
                $user_uuid
            );

        if (is_array($result) && isset($result['error'])) {

            $this->session->set_flashdata(
                'error',
                $result['error']
            );

            redirect('drystore/tambah');
            return;
        }

        $this->session->set_flashdata(
            'success',
            'Data Drystore berhasil disimpan.'
        );

        redirect('drystore');
    }

    /**
     * Edit
     */
    public function edit($uuid)
    {
        $drystore =
            $this->Drystore_model->get_by_uuid($uuid);

        if (!$drystore) {

            $this->session->set_flashdata(
                'error',
                'Data Drystore tidak ditemukan.'
            );

            redirect('drystore');
            return;
        }

        $data['title'] = 'Edit Drystore';

        $data['drystore'] = $drystore;

        $data['types'] =
            $this->Drystore_model->get_all_type();

        $data['wastes'] =
            $this->Drystore_model->get_all_waste();

        $data['matrix'] =
            $this->Drystore_model
                ->get_transaksi_matrix($uuid);
$data['active_nav'] = 'Drystore';
        $this->load->view('partials/head-packing', $data);
        $this->load->view('drystore/edit', $data);
        $this->load->view('partials/footer');
    }

    /**
     * Update
     */
    public function update($uuid)
    {
        if ($this->input->method() !== 'post') {
            redirect('drystore');
            return;
        }

        $drystore =
            $this->Drystore_model->get_by_uuid($uuid);

        if (!$drystore) {

            $this->session->set_flashdata(
                'error',
                'Data Drystore tidak ditemukan.'
            );

            redirect('drystore');
            return;
        }

        $user_uuid =
            $this->session->userdata('user_uuid');

        $result =
            $this->Drystore_model->update_harian(
                $uuid,
                $this->input->post(),
                $user_uuid
            );

        if (is_array($result) && isset($result['error'])) {

            $this->session->set_flashdata(
                'error',
                $result['error']
            );

            redirect(
                'drystore/edit/' . $uuid
            );

            return;
        }

        $this->session->set_flashdata(
            'success',
            'Data Drystore berhasil diperbarui.'
        );

        redirect('drystore');
    }

    public function type()
    {
        $data = array(
            'data' => $this->Drystore_model->get_type(),

            'active_nav' => 'type-ds'
        );

        $this->load->view('partials/head-packing', $data);
        $this->load->view('drystore/type', $data);
        $this->load->view('partials/footer');
    }
    public function simpan_type()
    {
        $rules_type = $this->Drystore_model->rules_type();
        $this->form_validation->set_rules($rules_type);

        if ($this->form_validation->run() === TRUE) {

            $insert_type = $this->Drystore_model->insert_type();
            if ($insert_type) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('drystore/type');
            } else {
                redirect('drystore/type');
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
            }
        }
    }

    public function edit_type($uuid)
    {
        $rules = $this->Drystore_model->rules_type();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Drystore_model->update_type($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
                redirect('drystore/type');
            } else {
                redirect('drystore/type');
                $this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
            }
        }

        $data = array(
            'data' => $this->Drystore_model->get_type_by_uuid($uuid),

            'active_nav' => 'type-ds'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('drystore/edit-type', $data);
        $this->load->view('partials/footer');
    }

    public function waste()
    {
        $data = array(
            'data' => $this->Drystore_model->get_waste(),

            'active_nav' => 'waste-ds'
        );

        $this->load->view('partials/head-packing', $data);
        $this->load->view('drystore/waste', $data);
        $this->load->view('partials/footer');
    }
    public function simpan_waste()
    {
        $rules_waste = $this->Drystore_model->rules_waste();
        $this->form_validation->set_rules($rules_waste);

        if ($this->form_validation->run() === TRUE) {

            $insert_waste = $this->Drystore_model->insert_waste();
            if ($insert_waste) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('drystore/waste');
            } else {
                redirect('drystore/waste');
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
            }
        }
    }

    public function edit_waste($uuid)
    {
        $rules = $this->Drystore_model->rules_waste();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->Drystore_model->update_waste($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di ubah.');
                redirect('drystore/waste');
            } else {
                redirect('drystore/waste');
                $this->session->set_flashdata('error_msg', 'Data gagal di ubah.');
            }
        }

        $data = array(
            'data' => $this->Drystore_model->get_waste_by_uuid($uuid),

            'active_nav' => 'waste-ds'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('drystore/edit-waste', $data);
        $this->load->view('partials/footer');
    }
}