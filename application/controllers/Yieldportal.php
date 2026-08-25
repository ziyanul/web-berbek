<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Yieldportal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Yield_model');
    }
    public function dashboard()
    {
        $data['title'] = 'Dashboard Yield';
        $data['monitoring_filkar'] =
            $this->Yield_model
            ->get_monitoring_filkar();
            $data['dashboard_mesin'] = $this->Yield_model
        ->get_dashboard_mesin_bulan_berjalan();
        $data['total_filkar'] =
            $this->Yield_model
            ->get_total_filkar();
        $data['monitoring_sortasi'] =
            $this->Yield_model
            ->get_monitoring_sortasi();
        $data['total_sortasi'] =
            $this->Yield_model
            ->get_total_sortasi();
        $data['varian'] = $this->Yield_model->get_varian_yield();
        $data['pvdc'] = $this->Yield_model->get_pvdc_wire();
        $data['bad_produk_varian'] =
            $this->Yield_model->get_bad_produk_varian(
                $data['varian']
            );
        // ==========================================
        // BAD PRODUK PER MESIN
        // revisi berikutnya
        // ==========================================
        $bad_mesin =
            $this->Yield_model
            ->get_bad_produk_mesin_dominan();
        $data['badproduk'] =
            $bad_mesin['badproduk'];
        $data['bad_produk_mesin'] =
            $bad_mesin['rows'];
        $data['total_sortasi_kg'] =
            $bad_mesin['total_sortasi_kg'];
        $this->load->view('dashboard/dashboard-yield', $data);
    }
    public function analisa()
    {
        if (!$this->Auth_model->current_user()) {
            redirect('login');
        }
        // master filter
        $data['varian'] = $this->Yield_model->get_master_varian();
        $data['mesin']  = $this->Yield_model->get_master_mesin();
        $data['badpro'] = $this->Yield_model->get_master_bad_produk();
        $data['active_nav'] = 'yield';
        $this->load->view('partials/head-yield', $data);
        $this->load->view('yield/analisa', $data);
        $this->load->view('partials/footer');
    }
    public function ajax_analisa()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $filter = [
            'tanggal_awal' => $this->input->post('tanggal_awal'),
            'tanggal_akhir' => $this->input->post('tanggal_akhir'),
            'varian'        => $this->input->post('varian'),
            'mesin'         => $this->input->post('mesin'),
            'badpro'        => $this->input->post('badpro')
        ];
        $ringkasan          = $this->Yield_model->get_ringkasan_analisa($filter);
        $monitoring         = $this->Yield_model->get_monitoring_analisa($filter);
        $bad_produk_varian  = $this->Yield_model->get_bad_produk_varian_analisa($filter);
        $bad_produk_mesin   = $this->Yield_model->get_bad_produk_mesin_analisa($filter);
        $detail_batch       = $this->Yield_model->get_detail_batch_analisa($filter);
        $response = [];
        $response['ringkasan'] = $this->load->view(
            'yield/ajax/ringkasan',
            [
                'ringkasan' => $ringkasan
            ],
            TRUE
        );
        $response['monitoring'] = $this->load->view(
            'yield/ajax/monitoring',
            [
                'monitoring' => $monitoring['rows'],
                'total'      => $monitoring['total']
            ],
            TRUE
        );
        $response['badproduk_varian'] = $this->load->view(
            'yield/ajax/badproduk_varian',
            $bad_produk_varian,
            TRUE
        );
        $response['badproduk_mesin'] = $this->load->view(
            'yield/ajax/badproduk_mesin',
            $bad_produk_mesin,
            TRUE
        );
        $response['detail_batch'] = $this->load->view(
            'yield/ajax/detail_batch',
            [
                'detail_batch' => $detail_batch
            ],
            TRUE
        );
        echo json_encode($response);
    }
}
