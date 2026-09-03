<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Filkar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Filkar_model');
        $this->load->library('form_validation');
        //$this->dberetort = $this->load->database('e-retort', TRUE);
        if (!$this->Auth_model->current_user()) {
            redirect('login');
        }
    }
    public function index()
    {
        $data = array(
            'data' => $this->Filkar_model->get_all(),
            'active_nav' => 'filkar'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('filkar/filkar_home', $data);
        $this->load->view('partials/footer');
    }
    public function filkarform()
    {

        $data = array(
            'data' => $this->Filkar_model->get_form(),
            'active_nav' => 'filkar'
        );

        $this->load->view('partials/head-form', $data);
        $this->load->view('filkar/filkar-form', $data);
        $this->load->view('partials/footer');
    }
    public function tambah()
    {
        $rules = $this->Filkar_model->rules2();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE) {
            $insert = $this->Filkar_model->insert();
            if ($insert) {
                $this->session->set_flashdata('success_msg', 'Data Filling Karantina berhasil di tambah.');
                redirect('filkar');
            } else {
                $this->session->set_flashdata('error_msg', 'Data Filling Karantina gagal di tambah.');
                redirect('filkar');
            }
        }
        $data = array(
            'batch' => $this->Filkar_model->get_batch(),
            'badpro'     => $this->Filkar_model->get_badpro('FILKAR'),
            'active_nav' => 'filkar'
        );

        $this->load->view('partials/head-yield', $data);
        $this->load->view('filkar/filkar_tambah', $data);
        $this->load->view('partials/footer');
    }
    public function get_kode_by_varian()
    {
        $varian_uuid = $this->input->get('varian');
        $data = $this->Filkar_model->get_kode_by_varian($varian_uuid);
        echo json_encode($data);
    }
    public function get_item_name($uuid)
    {
        $data = $this->Filkar_model->get_item_name($uuid);
        print_r(json_encode($data));
    }
    public function detail($uuid)
    {
        $data = [
            'data'       => $this->Filkar_model->get_by_uuid_join($uuid),
            'badpro'     => $this->Filkar_model->get_badpro_by_ref($uuid),
            'active_nav' => 'filkar'
        ];
        if (!$data['data']) {
            show_404();
        }
        $this->load->view('partials/head-yield', $data);
        $this->load->view('filkar/filkar_detail', $data);
        $this->load->view('partials/footer');
    }
    public function edit($uuid)
{
    // Pastikan UUID valid
    if (empty($uuid)) {
        $this->session->set_flashdata('error_msg', 'UUID tidak valid.');
        redirect('filkar');
    }

    $rules = $this->Filkar_model->rules2();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {

        $update = $this->Filkar_model->update($uuid);

        if ($update) {
            $this->session->set_flashdata(
                'success_msg',
                'Data Filling Karantina berhasil diubah.'
            );
        } else {
            $this->session->set_flashdata(
                'error_msg',
                'Data Filling Karantina gagal diubah.'
            );
        }

        redirect('filkar/');
    }

    // Ambil data filling yang sedang diedit
    $data_edit = $this->Filkar_model->get_by_uuid_join($uuid);

    if (!$data_edit) {
        $this->session->set_flashdata(
            'error_msg',
            'Data Filling Karantina tidak ditemukan.'
        );
        redirect('filkar');
    }

    $data = array(
        'data' => $data_edit,

        'badpro_input' => $this->Filkar_model->get_badpro_by_ref($uuid),

        'badpro_master' => $this->Filkar_model->get_badpro('FILKAR'),

        // INI YANG DIPERBAIKI
        'batch' => $this->Filkar_model->get_batch_edit(
            $data_edit->tbatch_uuid
        ),

        'active_nav' => 'filkar'
    );

    $this->load->view('partials/head-yield', $data);
    $this->load->view('filkar/filkar_edit', $data);
    $this->load->view('partials/footer');
}

    public function hapus($uuid)
    {
        if ($this->Filkar_model->delete($uuid)) {
            $this->session->set_flashdata(
                'success_msg',
                'Data Filling Karantina berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error_msg',
                'Data Filling Karantina gagal dihapus.'
            );
        }
        redirect('filkar');
    }
    public function approval($varian_uuid, $tanggal_kode, $role)
    {
        $update = $this->Filkar_model->approval($varian_uuid, $tanggal_kode, $role);
        if ($update) {
            $current_user = $this->Auth_model->current_user();
            echo json_encode([
                'status' => true,
                'fullname' => $current_user->fullname, // Nama pengguna yang menyetujui
                'message' => ucfirst($role) . ' berhasil di-ACC.',
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menyetujui ' . $role . '.',
            ]);
        }
    }
    public function form($varian_uuid, $tanggal_kode)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $v = $this->Filkar_model->get_by_tanggal($varian_uuid, $tanggal_kode);
        $logo = base_url("assets/img/cpi-logo.jpg");
        $html = '
  <html>
  <head>
  <title>FR-Prod-06 FILLING KARANTINA</title>
  <meta name="author" content="Arthur Herbert Fonzarelli">
  <meta name="keywords" content="fonzie, cool, ehhhhhhh">
  </head>
  <body>
  <style>
  @page { margin: 10px; }
  body {sans-serif; font-size: 14px; }
  table { width: 100%; border-collapse: collapse; }
  table tr td{border:1px solid #000;}
  table thead tr {background-color:#dbe5f1}
  table thead tr#standar{background-color:#b8cce4!important;}
  table.data tr th{border:1px solid #000;text-align:center;font-size:14px;}
  .data th, .data td { padding: 5px; }
  table.data tr td{text-align:center;}
  </style>
  <table width="100%">
  <tr>
  <td width="80">
  <table width="100%">
  <tbody>
  <tr>
  <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="150px"></td>
  </tr>
  </tbody>
  </table>
  </td>
  <td width="320">
  <table width="102%">
  <tbody>
  <tr>
  <td style="text-align:center;border-top:0;border-left:0;border-right:0;"><h2>FORM</h2></td>
  </tr>
  <tr>
  <td style="text-align:center;border:0; text-transform: uppercase;"><h2>FILLING KARANTINA</h2></td>
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
  <td style="border:0;height:30px;">&nbsp;FR-Prod-06</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:30px;">&nbsp;Revisi</td>
  <td style="border-left:0;border-right:0;height:30px;">:</td>
  <td style="border-left:0;border-right:0;height:30px;">&nbsp;0</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:30px;">&nbsp;Tanggal Efektif</td>
  <td style="border-left:0;border-right:0;height:30px;">:</td>
  <td style="border-left:0;border-right:0;height:30px;">&nbsp;01/04/2016</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;Halaman</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">:</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:30px;">&nbsp;1 dari 1</td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  </table>
  <table style="padding-top:10px; padding-bottom:10px;">
  <tbody>
  <tr>
  <td style="border:none; width:90px; text-align:left;">Tanggal</td>
  <td style="border:none; width:10px; text-align:left;">: </td>
  <td style="border:none; text-align:left;">' . $v[0]->tgl . '</td>
  </tr>
  <tr>
  <td style="border:none; width:90px; text-align:left;">Varian</td>
  <td style="border:none; width:10px; text-align:left;">: </td>
  <td style="border:none; text-align:left;">' . $v[0]->varian . '</td>
  </tr>
  </tbody>
  </table>
  <table class="data" style="margin-top: 5px;">
  <thead>
  <tr>
  <th rowspan="2">No.</th>
  <th rowspan="2">Kode Produk</th>
  <th colspan="2">Jam</th>
  <th rowspan="2" style="width: 50px; word-wrap: break-word; white-space: normal; text-align: center;">Jumlah Manpower</th>
  <th colspan="2">Jumlah</th>
  <th rowspan="2">Keterangan</th>
  </tr>
  <tr>
  <th>Mulai</th>
  <th>Selesai</th>
  <th>Box</th>
  <th>Kg</th>
  </tr>
  </thead>
  <tbody>';
        $no = 1;
        foreach ($v as $row) {
            $html .= '
    <tr>
    <td>' . $no . '</td>
    <td>' . $row->kode_prod . '</td>
    <td>' . date('H:i', strtotime($row->jam_mulai)) . '</td>
    <td>' . date('H:i', strtotime($row->jam_selesai)) . '</td>
    <td style="width: 50px; text-align: center;">' . $row->jml_mp . '</td>
    <td>' . $row->jumlah_box . '</td>
    <td>' . $row->jumlah_kg . '</td>
    <td>' . $row->keterangan . '</td>
    </tr>';
            $no++;
        }
        $html .= '</tbody></table>';
        $html .= '<br><table width="100%">
<tr>
<td style="width: 100px; text-align: center; background-color: #dbe5f1;">Mengetahui</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center; background-color: #dbe5f1;">Disetujui</td>
</tr>
<tr>
<td style="height: 80px; width: 100px;"></td>
<td style="height: 80px; border: none; width: 200px;"></td>
<td style="height: 80px; width: 100px;"></td>
</tr>
<tr>
<td style="width: 100px; text-align: center;">Koordinator</td>
<td style="border: none; width: 30px;"></td>
<td style="width: 100px; text-align: center;">Spv. Produksi</td>
</tr>
</table>';
        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('F4', 'potrait');
        $dompdf->render();
        $dompdf->stream("FR-Prod-06_Formulir_.pdf", array("Attachment" => false));
    }
    public function filkar_form($varian_uuid, $tanggal_kode)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $logo = base_url("assets/img/cpi-logo.jpg");
        $title = 'FILLING KARANTINA (WIP)';
        $no_form = 'FR-PROD-06';
        $revisi = '1';
        $tanggal_efektif = '01 - 04 - 2016';
        $halaman = '1 dari 1';
        // Panggil data dari model
        $data_kode = $this->Filkar_model->get_by_tanggal($varian_uuid, $tanggal_kode);
        $nav_form = $this->Filkar_model->get_nav_form($varian_uuid, $tanggal_kode);
        $badpro_headers = $this->Filkar_model->get_badpro_by_sub($varian_uuid, $tanggal_kode);
        $total = $this->Filkar_model->get_total_by_tanggal($varian_uuid, $tanggal_kode);
        $total_badpro = $this->Filkar_model->get_total_badpro($varian_uuid, $tanggal_kode);
        $data = [
            'data_kode' => $data_kode,
            'nav' => $nav_form,
            'badpro_headers' => $badpro_headers,
            'total' => $total,
            'total_badpro' => $total_badpro,
            'logo' => $logo,
            'title' => $title,
            'no_form' => $no_form,
            'revisi' => $revisi,
            'tanggal_efektif' => $tanggal_efektif,
            'halaman' => $halaman
        ];
        $html = $this->load->view('partials/head-form-pot', $data, true);
        $html .= $this->load->view('filkar/filkar_form', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('FOLIO', 'portrait');
        $dompdf->render();
        $dompdf->stream("$no_form $title", array("Attachment" => false));
    }
}
