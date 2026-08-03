<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Cekmesin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('am_model');
        $this->load->model('area_model');
        $this->load->model('cek_mesin_model');
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->config->load('relasi_uuid');
        $this->mp = $this->config->item('mp_uuid');
        if (!$this->auth_model->current_user()) {
            redirect('login');
        }
    }

    public function mp()
    {

        $data = array(
            'data' => $this->cek_mesin_model->get_mp(),

            'active_nav' => 'cekmesin-mp'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head-form', $data);
        $this->load->view('cek-mesin/cm-mp', $data);
        $this->load->view('partials/footer');
    }

    public function checklistmp($t_planning_uuid)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('mesin', 'Mesin', 'required', [
            'required' => 'Harus memilih mesin.'
        ]);
        $this->form_validation->set_rules('kegiatan[]', 'Kegiatan', 'callback_check_kegiatan_keterangan');
        if ($this->form_validation->run() == TRUE) {
            $insert = $this->cek_mesin_model->insert_cek_mesin($t_planning_uuid);
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                redirect('cekmesin_mp');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
                redirect('cekmesin_mp');
            }
        }
        $area_uuid = $this->mp;
        $data = array(
            'data' => $this->cek_mesin_model->get_plan_uuid($t_planning_uuid),
            'area' => $this->am_model->get_area(),
            'mesin' => $this->cek_mesin_model->get_mesin_with_usage_status($area_uuid, $t_planning_uuid),
            'active_nav' => 'cekmesin'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/cek-mp', $data);
        $this->load->view('partials/footer');
    }

    public function get_mesin_by_area($area_uuid, $t_planning_uuid)
    {
        $data = $this->cek_mesin_model->get_mesin_with_usage_status($area_uuid, $t_planning_uuid);
        echo json_encode($data); // Tambahkan debug di sini
        die();
    }

    public function check_kegiatan_keterangan($kegiatan)
    {
        $keterangan = $this->input->post('keterangan');
        if (empty($kegiatan)) {
            $this->form_validation->set_message('check_kegiatan_keterangan', 'Kegiatan harus dipilih.');
            return FALSE;
        }
        foreach ($kegiatan as $key => $value) {
            if ($value != 2 && empty($keterangan[$key])) {
                $this->form_validation->set_message('check_kegiatan_keterangan', 'Keterangan wajib di isi jika Kegiatan tidak dipilih.');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function check_akhir($uuid)
    {
        $update = $this->cek_mesin_model->update_check_akhir($uuid);
        if ($update) {
            $this->session->set_flashdata('success_msg', 'Data Berhasil di Tambahkan');
            echo json_encode(array('status' => TRUE));
        } else {
            $this->session->set_flashdata('error_msg', 'Data Gagal di Tambahkan');
            echo json_encode(array('status' => FALSE));
        }
    }

    public function keterangan($uuid)
    {
        $update = $this->cek_mesin_model->update_keterangan($uuid);
        if ($update) {
            echo json_encode(array('status' => TRUE, 'message' => 'Keterangan berhasil disimpan'));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Gagal menyimpan keterangan'));
        }
    }

    public function detailcekmesinmp($t_planning_uuid, $area_uuid)
    {

        $data = array(
            'data' => $this->cek_mesin_model->get_area_data($t_planning_uuid, $area_uuid),
            'nav'  => $this->cek_mesin_model->get_nav_by_tplanning($t_planning_uuid, $area_uuid),
            'active_nav' => 'cekmesin'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/detail-mp', $data);
        $this->load->view('partials/footer');
    }

    public function approval_cekmesin($t_planning_uuid, $area_uuid)
    {
        $update = $this->cek_mesin_model->approval_cekmesin($t_planning_uuid, $area_uuid);

        if ($update) {
            // Ambil data fullname dari user yang sedang login
            $current_user = $this->auth_model->current_user();

            echo json_encode([
                'status' => true,
                'fullname' => $current_user->fullname, // Pastikan ini benar
                'message' => 'Reservasi berhasil di-ACC.',
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menyetujui reservasi.',
            ]);
        }
    }
    public function approval_cekmesin2($t_planning_uuid, $area_uuid)
    {
        $update = $this->cek_mesin_model->approval_cekmesin2($t_planning_uuid, $area_uuid);

        if ($update) {
            // Ambil data fullname dari user yang sedang login
            $current_user = $this->auth_model->current_user();

            echo json_encode([
                'status' => true,
                'fullname' => $current_user->fullname, // Pastikan ini benar
                'message' => 'Reservasi berhasil di-ACC.',
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menyetujui reservasi.',
            ]);
        }
    }

    public function printmp($t_planning_uuid, $area_uuid)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        $cek_mesin = $this->cek_mesin_model->get_area_data($t_planning_uuid, $area_uuid);
        if (empty($cek_mesin)) {
            show_error('Data pengecekan mesin tidak ditemukan.');
            return;
        }
        $data = [
            'cek_mesin' => $cek_mesin,
            'logo'      => base_url('assets/img/cpi-logo.jpg'),
        ];

        $html = $this->load->view('pdf/cekmesin-mp', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('FOLIO', 'portrait');
        $dompdf->render();
        $dompdf->stream(
            'FR-Prod-14_FORM_PENGECEKAN_MESIN.pdf',
            ['Attachment' => false]
        );
    }



    public function dataitem()
    {
        $data = array(
            'data'      => $this->cek_mesin_model->get_item(),
            'active_nav' => 'item-cm'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head-form', $data);
        $this->load->view('cek-mesin/item-item', $data);
        $this->load->view('partials/footer');
    }

    public function tambahitem()
    {
        $rules = [
            [
                'field' => 'kegiatan[]',
                'label' => 'Kegiatan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {
            $insert = $this->cek_mesin_model->insert_item();
            if (!$insert) {
                $this->session->set_flashdata('error_msg', 'Sebagian data gagal disimpan.');
                redirect('cekmesin/dataitem');
            }
            $this->session->set_flashdata('success_msg', 'Semua data berhasil disimpan.');
            redirect('cekmesin/dataitem');
        }

        $data = array(
            'area' => $this->area_model->get_all(),
            'active_nav' => 'kegiatan-am'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/tambah-item', $data);
        $this->load->view('partials/footer');
    }

    public function edititem($uuid)
    {
        $rules = [
            [
                'field' => 'kegiatan',
                'label' => 'Kegiatan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            $update = $this->cek_mesin_model->update_item($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data berhasil di simpan.');
                redirect('cekmesin/dataitem');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal di simpan.');
                redirect('cekmesin/dataitem');
            }
        }

        $data = array(
            'area'      => $this->area_model->get_all(),
            'data'      => $this->cek_mesin_model->get_by_uuid($uuid),
            'active_nav' => 'kegiatan-am'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('cek-mesin/edit-item', $data);
        $this->load->view('partials/footer');
    }

    public function get_kegiatan_by_mesin($mesin_uuid)
    {
        $data = $this->cek_mesin_model->get_kegiatan_by_mesin($mesin_uuid);
        print_r(json_encode($data));
    }
}
