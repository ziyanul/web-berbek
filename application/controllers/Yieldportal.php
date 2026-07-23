<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yieldportal extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Yield_model');
    }

    public function dashboard()
    {
        $result = $this->Yield_model->get_yield_produksi();

        $data['monitoring'] = $result['rows'];
        $data['total']      = $result['total'];

        $data['varian'] = $this->Yield_model->get_varian_yield();

        $data['bad_produk_varian'] =
            $this->Yield_model->get_bad_produk_varian();
        $data['badproduk'] =
        $this->Yield_model->get_master_bad_produk();

        $data['bad_produk_mesin'] =
            $this->Yield_model->get_bad_produk_mesin();

        $this->load->view('dashboard/dashboard-yield',$data);
    }

    public function analisa()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $mesin  = $this->input->get('mesin');
        $varian = $this->input->get('varian');
        $badpro = $this->input->get('badpro');

        $tanggal_awal  = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');

        $data['filter'] = [
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'mesin'=>$mesin,
            'varian'=>$varian,
            'badpro'=>$badpro,
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_akhir'=>$tanggal_akhir,
        ];

        $data['monitoring'] =
            $this->Yield_model->monitoring_filter($data['filter']);

        $data['varian_list'] =
            $this->Yield_model->list_varian();

        $data['mesin_list'] =
            $this->Yield_model->list_mesin();

        $data['badpro_list'] =
            $this->Yield_model->list_badpro();
            $data['active_nav'] = 'yield';
        $this->load->view('partials/head-yield',$data);
        $this->load->view('yield/analisa',$data);
        $this->load->view('partials/footer');
    }
}