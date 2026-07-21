<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pm_model');
        $this->load->model('Am_model');
        $this->load->model('Monitor_model');
        $this->load->model('Partrequest_model');
        $this->load->model('Yield_model');
    }
    public function index()
    {
        $this->load->view('portal/portal');
    }
    public function paperless()
    {
        $this->load->view('dashboard/dashboard-paperless');
    }
    public function maintenance()
    {
        $data  = array(
            'maintenance' => $this->Pm_model->count_maintenance(),
            'top_pm' => $this->Pm_model->get_top_pm(),
            'monitor' => $this->Monitor_model->get_monitor_count(),
            'pengajuan' => $this->Partrequest_model->get_pengajuan_count(),
            'auto' => $this->Am_model->count_am()
        );
        $this->load->view('dashboard/dashboard-maintenance', $data);
    }
    public function yield()
    {
        $result = $this->Yield_model->monitoring_bulan($bulan=NULL, $tahun=NULL);

$data['monitoring'] = $result['rows'];
$data['total'] = $result['total'];

        $this->load->view('dashboard/dashboard-yield', $data);
    }
}