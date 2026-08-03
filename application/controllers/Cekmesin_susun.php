<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Cekmesin_susun extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Am_model');
        $this->load->model('Area_model');
        $this->load->model('Cekmesin_susun_model');
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->config->load('relasi_uuid');
        $this->susun = $this->config->item('susun_uuid');

        if (!$this->auth_model->current_user()) {
            redirect('login');
        }
    }

    public function index()
    {

        $data = array(
            'data' => $this->Cekmesin_susun_model->get_susun(),
            'susun_uuid' => $this->susun,
            'active_nav' => 'cekmesin_susun'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head-form', $data);
        $this->load->view('cekmesin_susun/home', $data);
        $this->load->view('partials/footer');
    }

    public function checklist($t_planning_uuid)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mesin', 'Mesin', 'required', [
            'required' => 'Harus memilih mesin.'
        ]);

        $this->form_validation->set_rules('kegiatan[]', 'Kegiatan', 'callback_check_kegiatan_keterangan');

        if ($this->form_validation->run() == TRUE) {
            $insert = $this->Cekmesin_susun_model->insert_cek_mesin($t_planning_uuid);
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data berhasil disimpan.');
                redirect('cekmesin_susun');
            } else {
                $this->session->set_flashdata('error_msg', 'Data gagal disimpan.');
                redirect('cekmesin_susun');
            }
        }
        $area_uuid = $this->susun;
        $data = array(
            'data' => $this->Cekmesin_susun_model->get_plan_uuid($t_planning_uuid),
            'area' => $this->Area_model->get_all(),
            'mesin' => $this->Cekmesin_susun_model->get_mesin_with_usage_status($area_uuid, $t_planning_uuid),
            'active_nav' => 'cekmesin_susun'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head-form', $data);
        $this->load->view('cekmesin_susun/checklist', $data);
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
        $update = $this->Cekmesin_susun_model->update_check_akhir($uuid);
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
        $update = $this->Cekmesin_susun_model->update_keterangan($uuid);
        if ($update) {
            echo json_encode(array('status' => TRUE, 'message' => 'Keterangan berhasil disimpan'));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Gagal menyimpan keterangan'));
        }
    }
    public function detail($t_planning_uuid, $area_uuid)
    {

        $data = array(
            'data' => $this->Cekmesin_susun_model->get_area_data($t_planning_uuid, $area_uuid),
            'nav'  => $this->Cekmesin_susun_model->get_nav_by_tplanning($t_planning_uuid, $area_uuid),
            'active_nav' => 'cekmesin_susun'
        );
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        $this->load->view('partials/head-form', $data);
        $this->load->view('cekmesin_susun/detail', $data);
        $this->load->view('partials/footer');
    }

    public function approval_cekmesin($t_planning_uuid, $area_uuid)
    {
        $update = $this->Cekmesin_susun_model->approval_cekmesin($t_planning_uuid, $area_uuid);

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
        $update = $this->Cekmesin_susun_model->approval_cekmesin2($t_planning_uuid, $area_uuid);

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



    public function print($t_planning_uuid, $area_uuid)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        $cek_mesin = $this->Cekmesin_susun_model->get_area_data($t_planning_uuid, $area_uuid);
        $logo = base_url("assets/img/cpi-logo.jpg");

        $html = '
        <html>
        <head>
        <title>FR-Prod-14 FORM PENGECEKAN MESIN SUSUN</title>
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
        table.data tr th{border:1px solid #000;text-align:center;font-size:12px;}
        .data th, .data td { padding: 2px; }
        table.data tr td{text-align:center;}
        </style>
        <table width="100%">
        <tr>
        <td width="70">
        <table width="100%">
        <tbody>
        <tr>
        <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="110px"></td>
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
        <td style="text-align:center;border:0; text-transform: uppercase;"><h2>PENGECEKAN MESIN</h2></td>
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
        <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;4 dari 6</td>
        </tr>
        </tbody>
        </table>
        </td>
        </tr>
        </table>

        <table style="padding-top:10px; padding-bottom:10px;">
        <tbody>
        <tr>
        <td style="border:none; width:60px; text-align:left;">Area</td>
        <td style="border:none; width:10px; text-align:left;">: </td>
        <td style="border:none; text-align:left;">Filler & Susun</td>
        </tr>
        <tr>
        <td style="border:none; width:60px; text-align:left;">Tanggal</td>
        <td style="border:none; width:10px; text-align:left;">: </td>
        <td style="border:none; text-align:left;">' . $cek_mesin[0]->tgl . '</td>
        <td style="border:none; width:60px; text-align:left;">Varian</td>
        <td style="border:none; width:10px; text-align:left;">: </td>
        <td style="border:none; width:450px; text-align:left;">' . $cek_mesin[0]->varian . ' ( ' .$cek_mesin[0]->keterangan. ' )</td>
        </tr>
        </tbody>
        </table><br>

        <table class="data" width="100%">
       <thead>
            <tr>
                <th rowspan="2" width="1">No</th>
                <th rowspan="2" style="text-align:left; width:130px;">Item</th>
                <th colspan="2">Checklist Awal Produksi</th>
                <th rowspan="2" style="width:80px;">Keterangan</th>
                <th colspan="2" style="border-right: 3px solid #000000;">Paraf</th>
                <th colspan="2">Checklist Akhir Produksi</th>
                <th rowspan="2" style="width:80px;">Keterangan</th>
                <th colspan="2">Paraf</th>
            </tr>
            <tr>
                <th style="width:40px;">Ya</th>
                <th style="width:40px;">Tidak</th>
                <th style="width:60px;">Prod</th>
                <th style="width:60px; border-right: 3px solid #000000;">QC</th>
                <th style="width:40px;">Ya</th>
                <th style="width:40px;">Tidak</th>
                <th style="width:60px;">Prod</th>
                <th style="width:60px;">QC</th>
            </tr>
        </thead>
        <tbody>';

        $last_mesin = null;
        $mesin_no = 'A'; // Huruf awal untuk penomoran mesin
        $item_no = 1; // Nomor urut item dalam mesin

        foreach ($cek_mesin as $row) {
            if ($last_mesin !== $row->mesin) {
                // Tambahkan header untuk mesin baru
                if ($last_mesin !== null) {
                    $html .= '</tbody>';
                }
                $html .= '<tr>';
                $html .= '<td style="text-align:left; border-right: 3px solid #000000;" colspan="7"><strong>' . $mesin_no . '. ' . htmlspecialchars($row->mesin) . '</strong></td>';
                $html .= '<td colspan="5"></td>';
                $html .= '</tr>';

                $last_mesin = $row->mesin;
                $mesin_no++;
                $item_no = 1; // Reset nomor item untuk mesin baru
            }

            // Baris data item
            $html .= '<tr>';
            $html .= '<td>' . $item_no . '</td>';
            $html .= '<td style="text-align:left;">' . htmlspecialchars($row->item) . '</td>';
            $html .= '<td style="font-size: 12px;">' . ($row->checklist == 2 ? '&check;' : '-') . '</td>';
            $html .= '<td style="font-size: 12px;">' . ($row->checklist == 0 ? 'x' : '-') . '</td>';
            $html .= '<td>' . ($row->keterangan ?: '-') . '</td>';
            $html .= '<td>' . ($row->fullname ?: '') . '</td>';
            $html .= '<td style="border-right: 3px solid #000000;">' . ($row->paraf_qc ?? '') . '</td>';
            $html .= '<td style="font-size: 12px;">' . ($row->checklist2 == 2 ? '&check;' : '-') . '</td>';
            $html .= '<td style="font-size: 12px;">' . ($row->checklist2 == 1 ? 'x' : '-') . '</td>';
            $html .= '<td>' . ($row->keterangan2 ?: '-') . '</td>';
            $html .= '<td>' . ($row->paraf_prod ?: '') . '</td>';
            $html .= '<td>' . ($row->paraf_qc ?? '') . '</td>';
            $html .= '</tr>';

            $item_no++;
        }

        $html .= '</tbody></table>';
        $html .= '<br><table width="100%">
<tr>
                    <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Diverifikasi Oleh</b></td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 200px; text-align: center; background-color: #dbe5f1;"><b>Disetujui Oleh</b></td>
                </tr>';
        $html .= '<tr>';
        $html .= '<td style="text-align: center; height: 50px; width: 200px;">' . $row->foreman . '</td>';
        $html .= '<td style="height: 50px; border: none; width: 80px;"></td>';
        $html .= '<td style="text-align: center; height: 50px; width: 200px;">' . $row->spv . '</td>';
        $html .= '</tr>
                <tr>
                    <td style="width: 200px; text-align: center;">Foreman/Lady</td>
                    <td style="border: none; width: 30px;"></td>
                    <td style="width: 200px; text-align: center;">Spv.Produksi</td>
                </tr>
</table>';

        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('FOLIO', 'portrait');
        $dompdf->render();
        $dompdf->stream("FR-Prod-14_FORM_PENGECEKAN_MESIN.pdf", array("Attachment" => false));
    }

    public function get_kegiatan_by_mesin($mesin_uuid)
    {
        $data = $this->Cekmesin_susun_model->get_kegiatan_by_mesin($mesin_uuid);
        print_r(json_encode($data));
    }

    public function get_mesin_by_area($area_uuid, $t_planning_uuid)
    {
        $data = $this->Cekmesin_susun_model->get_mesin_with_usage_status($area_uuid, $t_planning_uuid);
        echo json_encode($data); // Tambahkan debug di sini
        die();
    }
}
