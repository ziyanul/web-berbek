<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
}
