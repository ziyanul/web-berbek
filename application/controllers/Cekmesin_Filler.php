<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Cekmesin_filler extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('am_model');
        $this->load->model('area_model');
        $this->load->model('Cekmesin_filler_model');
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->config->load('relasi_uuid');
		$this->filler = $this->config->item('filler_uuid');

        if(!$this->auth_model->current_user()){
            redirect('login');
        }
    }

    public function index()
    {

        $data = array(
            'data' => $this->Cekmesin_filler_model->get_all(),

            'active_nav' => 'cekmesin_filler'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head', $data);
        $this->load->view('cekmesin_filler/cekmesin_filler', $data);
        $this->load->view('partials/footer');
    }

    public function checklist_awalproses($t_planning_uuid)
    {   
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mesin', 'Mesin', 'required', [
            'required' => 'Harus memilih mesin.'
        ]);
    
        $this->form_validation->set_rules('kegiatan[]', 'Kegiatan', 'callback_check_kegiatan_keterangan');
    
        if ($this->form_validation->run() == TRUE) {
            $insert = $this->Cekmesin_filler_model->insert_cek_mesin($t_planning_uuid);
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                redirect('cekmesin_filler');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
                redirect('cekmesin_filler');
            }
        }
        $area_uuid = $this->filler;
        $data = array(
            'data' => $this->Cekmesin_filler_model->get_plan_uuid($t_planning_uuid),
            'area' => $this->am_model->get_area(),
            'mesin' => $this->Cekmesin_filler_model->get_mesin_with_usage_status($area_uuid, $t_planning_uuid),
            'active_nav' => 'cekmesin_filler'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head', $data);
        $this->load->view('cekmesin_filler/cekmesin_filler_cheklist', $data);
        $this->load->view('partials/footer');
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

    public function detail_awalproses($t_planning_uuid, $area_uuid)
{
    $result = $this->Cekmesin_filler_model->get_area_data($t_planning_uuid, $area_uuid);

    $data = array(
        'data' => $result['data'],
        'mesin_headers' => $result['mesin_headers'],
        'nav'  => $this->Cekmesin_filler_model->get_by_uuid($t_planning_uuid, $area_uuid),
        'active_nav' => 'cekmesin_filler'
    );

    $this->load->view('partials/head', $data);
    $this->load->view('cekmesin_filler/cekmesin_filler_detail', $data);
    $this->load->view('partials/footer');
}

    public function approval_cekmesin($t_planning_uuid, $area_uuid)
    {
        $update = $this->Cekmesin_filler_model->approval_cekmesin($t_planning_uuid, $area_uuid);

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
        $update = $this->Cekmesin_filler_model->approval_cekmesin2($t_planning_uuid, $area_uuid);

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

    public function get_kegiatan_by_mesin($mesin_uuid)
    {
    $data = $this->Cekmesin_filler_model->get_kegiatan_by_mesin($mesin_uuid);
    print_r(json_encode($data));
    }

    public function get_mesin_by_area($area_uuid,$t_planning_uuid)
    {
        $data = $this->Cekmesin_filler_model->get_mesin_with_usage_status($area_uuid,$t_planning_uuid);
        echo json_encode($data); // Tambahkan debug di sini
        die();
    }

        public function form_awalproses($t_planning_uuid, $area_uuid)
    {
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    $cek_mesin = $this->Cekmesin_filler_model->get_area_data($t_planning_uuid, $area_uuid);
    $logo = base_url("assets/img/cpi-logo.jpg");

    $html ='
    <html>
    <head>
        <title>FR-Prod-14 FORM PENGECEKAN MESIN MP</title>
            <meta name="author" content="Arthur Herbert Fonzarelli">
            <meta name="keywords" content="fonzie, cool, ehhhhhhh">
            </head>
            <body>

            <style>
            @page { margin: 10px; }
            body {sans-serif; font-size: 10px; }
            table { width: 100%; border-collapse: collapse; }
            table tr td{border:1px solid #000;}
            table thead tr {background-color:#dbe5f1}
            table thead tr#standar{background-color:#b8cce4!important;}
            table.data tr th{border:1px solid #000;text-align:center;font-size:12px;}
            .data th, .data td { padding: 5px; }
            table.data tr td{text-align:center;}
            </style>
            <table width="100%">
            <tr>
            <td width="30">
            <table width="100%">
            <tbody>
            <tr>
            <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="'.$logo.'" width="110px"></td>
            </tr>
            </tbody>
            </table>
            </td>
            <td width="660">
            <table width="102%">
            <tbody>
                <tr>
                    <td style="text-align:center;border-top:0;border-left:0;border-right:0;font-size:16px;"><h4>FORM</h4></td>
                </tr>
                <tr>
                    <td style="text-align:center;border:0; text-transform: uppercase;font-size:16px;"><h4>PENGECEKAN MESIN</h4></td>
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
                    <td style="border:0;height:30px;">&nbsp;FR-Prod-14</td> 
                </tr>
                <tr>
                    <td style="border-left:0;border-right:0;height:30px;">&nbsp;Revisi</td>
                    <td style="border-left:0;border-right:0;height:30px;">:</td>
                    <td style="border-left:0;border-right:0;height:30px;">&nbsp;1</td> 
                </tr>
                <tr>
                    <td style="border-left:0;border-right:0;height:30px;">&nbsp;Tanggal Efektif</td>
                    <td style="border-left:0;border-right:0;height:30px;">:</td>
					<td style="border-left:0;border-right:0;height:30px;">&nbsp;02/01/2024</td> 
                </tr>
                <tr>
                    <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;Halaman</td>
                    <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">:</td>
                    <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;2 dari 6</td> 
                </tr>
            </tbody>
            </table>
            </td>
            </tr>
            </table>

            <table style="padding-top:10px; padding-bottom:10px;font-size:12px;">
            <tbody>
                <tr>
                    <td style="border:none; width:80px; text-align:left;">Area</td>
                    <td style="border:none; width:10px; text-align:left;">: </td>
                    <td style="border:none; text-align:left;">'.$cek_mesin['data'][0]->area.'</td>
                </tr>
                <tr>
                    <td style="border:none; width:80px; text-align:left;">Tanggal</td>
                    <td style="border:none; width:10px; text-align:left;">: </td>
                    <td style="border:none; text-align:left;">'.$cek_mesin['data'][0]->tgl.'</td>
                    <td style="border:none; width:80px; text-align:left;">Varian</td>
                    <td style="border:none; width:10px; text-align:left;">: </td>
                    <td style="border:none; width:850px; text-align:left;">'.$cek_mesin['data'][0]->varian.'</td>
            </tr>
            </tbody>
            </table><br>';

            $html .= '<table class="data" width="100%" cellspacing="0">';
            $html .= '<thead class="table bg-info text-light">';
            $html .= '<tr>';
            $html .= '<th rowspan="2" style="width:1px; text-align:center;">No</th>';
            $html .= '<th rowspan="2" style="width:100px; text-align:left;">Item</th>';
            $html .= '<th colspan="' . (isset($cek_mesin['mesin_headers']) ? count($cek_mesin['mesin_headers']) : 0) . '" style="text-align:center;">Checklist (&check;) Mesin</th>';
            $html .= '<th rowspan="2" style="width:150px; text-align:center;">Keterangan</th>';
            $html .= '<th colspan="2" style="width:80px; text-align:center;">Paraf</th>';
            $html .= '</tr>';
            $html .= '<tr>';

            if (isset($cek_mesin['mesin_headers']) && !empty($cek_mesin['mesin_headers'])) {
                foreach ($cek_mesin['mesin_headers'] as $uuid_mesin => $nama_mesin) {
                    $html .= '<th style="width:50px; text-align:center;">' . $nama_mesin . '</th>';
                }
            }
            $html .= '<th style="width:40px; text-align:center;">Prod</th>';
            $html .= '<th style="width:40px; text-align:center;">QC</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            $no = 1;
            $grouped_data = [];
            foreach ($cek_mesin['data'] as $row) {
                $grouped_data[$row->item][] = $row;
            }
            foreach ($grouped_data as $item => $rows) {
                $html .= '<tr>';
                $html .= '<td style="width:1px; text-align:center;">' . $no . '</td>';
                $html .= '<td style="text-align:left;">' . $item . '</td>';

                foreach ($cek_mesin['mesin_headers'] as $mesin_uuid => $nama_mesin) {
                    $checklist = "-"; // Default kosong
                    foreach ($rows as $row) {
                        if ($row->mesin_uuid === $mesin_uuid) {
                            $checklist = $row->checklist == 2 ? '&check;' : 'x';
                            break;
                        }
                    }
                    $html .= '<td style="text-align:center;font-size:16px;">' . $checklist . '</td>';
                }

                $html .= '<td style="text-align:center;">';
                $keterangan_list = [];
                foreach ($rows as $row) {
                    if ($row->checklist == 0) {
                        $keterangan_list[] = '(' . $cek_mesin['mesin_headers'][$row->mesin_uuid] . ') - ' . $row->keterangan;
                    }
                }
                $html .= !empty($keterangan_list) ? implode("<br>", $keterangan_list) : "-";
                $html .= '</td>';
                $html .= '<td>'. ($rows[0]->paraf_prod ?: '-') .'</td>';
                $html .= '<td style="text-align:center;">'.($rows[0]->paraf_qc ?: '-').'</td>';
                $html .= '</tr>';

                $no++;
            }
    
$html .= '</tbody></table>';
$html .= '<br><table width="100%">
                <tr>
                    <td style="width: 150px; text-align: center; background-color: #dbe5f1;"><b>Dilaksanakan Oleh</b></td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 150px; text-align: center; background-color: #dbe5f1;"><b>Diverifikasi Oleh</b></td> 
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 150px; text-align: center; background-color: #dbe5f1;"><b>Disetujui Oleh</b></td> 
                </tr>
                <tr>
                    <td style="height: 50px; width: 150px;"></td>
                    <td style="height: 50px; border: none; width: 250px;"></td>
                    <td style="height: 50px; width: 150px;"></td> 
                    <td style="height: 50px; border: none; width: 250px;"></td>
                    <td style="height: 50px; width: 150px;"></td> 
                </tr>
                <tr>
                    <td style="width: 150px; text-align: center;">Checker</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 150px; text-align: center;">Foreman/Lady</td> 
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 150px; text-align: center;">Spv.Produksi</td> 
                </tr>
            </table>';

    $html .= '</body></html>';
    $dompdf->loadHtml($html);
    $dompdf->setPaper('FOLIO', 'landscape');
    $dompdf->render();
    $dompdf->stream("FR-Prod-14_FORM_PENGECEKAN_MESIN.pdf", array("Attachment" => false));
}

}