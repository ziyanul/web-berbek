<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;

class Tools_Mesin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('TLmesin_model');
        $this->load->model('area_model');    
        $this->load->library('form_validation');
    }

    public function index()
    {
        $rules = $this->TLmesin_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {
            
            $insert = $this->TLmesin_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Tools berhasil di tambah.');
                redirect('Tools_Mesin');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Tools gagal di tambah.');
                redirect('Tools_Mesin');
            }
        }

        $data = array(
            'data' => $this->TLmesin_model->get_all(),
            'area' => $this->area_model->get_all(),
            'active_nav' => 'tl_mesin'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/tl_mesin', $data);
        $this->load->view('partials/footer');
    }

    public function edit($uuid)
    {
        $rules = $this->TLmesin_model->rules();
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() === TRUE) {
            
            $are = $this->input->post('area');
            $update = $this->TLmesin_model->update($uuid);
            if ($update) {
                $this->session->set_flashdata('success_msg', 'Data Tools berhasil di ubah.');
                redirect('Tools_Mesin/detail/'.$are);
            } else {
                $this->session->set_flashdata('error_msg', 'Data Tools gagal di ubah.');
                redirect('Tools_Mesin/detail/'.$are);
            }
        }

        $data = array(
            'data' => $this->TLmesin_model->get_by_uuid($uuid),
            'area' => $this->area_model->get_all(),
            'active_nav' => 'tl_mesin'
        );
 
        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/tl_edit', $data);
        $this->load->view('partials/footer');
    }

    public function detail($area_uuid)
    {
        $data = array(
            'data' => $this->TLmesin_model->get_by_area($area_uuid),
            'active_nav' => 'tl_mesin'
        );

        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/tl_detail', $data);
        $this->load->view('partials/footer');
    }

    public function data()
    {
        $data = array(
            'data' => $this->TLmesin_model->get_group_area_bulan(),
            'active_nav' => 'f-tl'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/data', $data);
        $this->load->view('partials/footer');
    }

    public function tambahdata()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            
            $insert = $this->TLmesin_model->insert_form();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Tools berhasil di ubah.');
                redirect('Tools_Mesin/data/');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Tools gagal di ubah.');
                redirect('Tools_Mesin/data/');
            }
        }

        $data = array(
        // 'data' => $this->TLmesin_model->get_by_uuid($uuid),
            'area' => $this->area_model->get_all(),
            'active_nav' => 'f-tl'
        );
        
        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/tambah-data', $data);
        $this->load->view('partials/footer');
    }

    public function get_tools_by_area($area_uuid)
    {
        $data = $this->TLmesin_model->get_tools_by_area($area_uuid);
        print_r(json_encode($data));
    }

    public function formdetail($area_uuid, $bulan)
    {
        $data = array(
            'data' => $this->TLmesin_model->get_by_area_bulan($area_uuid, $bulan),
            'active_nav' => 'f-tl'
        );
   
        $this->load->view('partials/head', $data);
        $this->load->view('tl_mesin/form-detail', $data);
        $this->load->view('partials/footer');
    }

    public function printform($bulan) {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

    $dataPerArea = $this->TLmesin_model->get_by_bulan($bulan); // Ambil data terstruktur
    $nama_bulan = $this->TLmesin_model->nama_bulan($bulan);
    $logo = base_url("assets/img/cpi-logo.jpg");

    $totalArea = count($dataPerArea);
    $currentPage = 1; 

    $html = '            <html>
    <head>
    <title>FR-Prod-25 Pengecekan Tools Mesin</title>
    <meta name="author" content="Arthur Herbert Fonzarelli">
    <meta name="keywords" content="fonzie, cool, ehhhhhhh">
    </head>
    <body>
    <style>
    @page { margin: 5px; }
    body {sans-serif; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; }
    table tr td{border:1px solid #000;}
    table thead tr {background-color:#dbe5f1}
    table thead tr#standar{background-color:#b8cce4!important;}
    table.data tr th{border:1px solid #000;text-align:center;font-size:11px;}
    .data th, .data td { padding: 2px; }
    table.data tr td{text-align:center;}
    </style>';

    foreach ($dataPerArea as $area => $areaData) {
        $html .= '
        <div class="header">
        <table width="100%">
        <tr>
        <td width="70">
        <table width="100%">
        <tbody>
        <tr>
        <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="110px"></td>
        </tr>
        </tbody>
        </table>
        </td>
        <td width="380">
        <table width="102%">
        <tbody>
        <tr>
            <td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h2>FORM</h2></td>
        </tr>
        <tr>
        <td style="text-align:center;border:0; text-transform: uppercase;"><h2>Pengecekan Tools Mesin</h2></td>
        </tr>
        </tbody>
        </table>
        </td>
        <td>
        <table width="101%" style="margin-left:-1px;">
        <tbody>
        <tr>
        <td style="border:0;height:30px;">&nbsp;No. Dokumen</td>
        <td style="border:0;height:30px;">:</td>
        <td style="border:0;height:30px;">&nbsp;FR-Prod-23</td> 
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:30px;">&nbsp;Revisi</td>
        <td style="border-left:0;border-right:0;height:30px;">:</td>
        <td style="border-left:0;border-right:0;height:30px;">&nbsp;0</td> 
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;height:30px;">&nbsp;Tanggal Efektif</td>
        <td style="border-left:0;border-right:0;height:30px;">:</td>
        <td style="border-left:0;border-right:0;height:30px;">&nbsp;30/08/2021</td> 
        </tr>
        <tr>
        <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;Halaman</td>
        <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">:</td>
        <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;' . $currentPage . ' dari ' . $totalArea . '</td> 
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </table>
        </div>

        <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>
        <tr>
        <td style="border:none; width:60px; text-align:left;">&nbsp;Area</td>
        <td style="border:none; width:10px; text-align:left;">:</td>
        <td style="border:none; text-align:left;">&nbsp;'. $area .'</td>
        </tr>
        <tr>
        <td style="border:none; width:60px; text-align:left;">&nbsp;Bulan</td>
        <td style="border:none; width:10px; text-align:left;">:</td>
        <td style="border:none; text-align:left;">&nbsp;' . $nama_bulan .'</td>
        </tr>
        </tbody>
        </table>
        <table class="data" width="100%" style="margin-top:10px;">
        <thead>
        <tr><th rowspan="3" style="width: 15%;">Tanggal</th>';
        foreach ($areaData['tools'] as $tool) {
            $html .= '<th class="align-middle text-center" colspan="2">Kondisi (&#x2713;)</th>';
        }
        $html .= '<th class="align-middle text-center" rowspan="3">Keterangan</th></tr><tr>';
        
        foreach ($areaData['tools'] as $tool) {
            $html .= '<th class="align-middle text-center">Bersih</th><th class="align-middle text-center">Kelengkapan</th>';
        }
        $html .= '</tr><tr>';
        
        foreach ($areaData['tools'] as $tool) {
            $html .= '<th class="align-middle text-center" colspan="2">' . $tool . '</th>';
        }
        $html .= '</tr>
        </thead>
        <tbody>';
        

        foreach ($areaData['data'] as $tanggal => $tools) {
            $html .= '<tr>
            <td>' . $tanggal . '</td>';
            foreach ($areaData['tools'] as $tool) {
                $html .= '<td style="font-size: 14px;">' . ($tools[$tool]['kondisi'] ?? '-') . '</td>';
                $html .= '<td style="font-size: 14px;">' . ($tools[$tool]['kelengkapan'] ?? '-') . '</td>';
            }
            $html .= '<td>' . ($tools[array_key_first($tools)]['keterangan'] ?? '-') . '</td></tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<br>
        <div class="footer">
        <table width="100%" style="margin-top: 10px">
        <tr>
        <td style="width: 100px; text-align: center;">Diisi Oleh</td>
        <td style="border: none; width: 30px;"></td>
        <td style="width: 100px; text-align: center;">Mengetahui</td>
        <td style="border: none; width: 30px;"></td>
        <td style="width: 100px; text-align: center;">Menyetujui</td>
        </tr>
        <tr>
        <td style="height: 60px;"></td>
        <td style="border: none;"></td>
        <td style="height: 60px;"></td>
        <td style="border: none;"></td>
        <td style="height: 60px;"></td>
        </tr>
        <tr>
        <td style="text-align: center;">Checker</td>
        <td style="border: none;"></td>
        <td style="text-align: center;">Foreman/Lady Produksi</td>
        <td style="border: none;"></td>
        <td style="text-align: center;">Spv. Produksi</td>
        </tr>
        </table>
        </div>
        <div style="page-break-before: always;"></div>';
        $currentPage++;
    }

    $html .= '</body></html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('F4', 'potrait');
    $dompdf->render();
    $dompdf->stream('Laporan_Tools_' . $bulan . '.pdf', array('Attachment' => false));
}


}