<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class pergantian_varian extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('auth_model');
    $this->load->model('pg_varian_model');
    $this->load->model('varian_model');
    $this->load->library('form_validation');

    if (!$this->auth_model->current_user()) {
      redirect('login');
    }
  }

  public function retort()
  {
    $data = array(
      'data' => $this->pg_varian_model->get_all_retort(),
      'active_nav' => 'pergantian_varian_retort'
    );
    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_varian', $data);
    $this->load->view('partials/footer');
  }

  public function tambah()
  {
    $rules = $this->pg_varian_model->rules();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {
      $insert = $this->pg_varian_model->insert_retort();

      $date = date('2025-02-06'); // Format tanggal yang benar
      $data = $this->pg_varian_model->get_transition_varian_by_date($date);

      if ($insert && !empty($data['latest']) && !empty($data['latest']->uuid)) {
        $update = $this->pg_varian_model->update_stuffer($date);
      } else {
        $update = false;
      }

      if ($insert && $update) {
        $this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil ditambahkan dan status diperbarui.');
      } else {
        $this->session->set_flashdata('error_msg', 'Gagal menambahkan data atau memperbarui status.');
      }
      redirect('pergantian_varian_retort');
    }

    $varian_name_1 = $this->input->post('varian_name_1');
    $varian_name_2 = $this->input->post('varian_name_2');
    $date = date('2025-02-06');
    $data = array(
      'data'       => $this->pg_varian_model->get_transition_varian_by_date($date),
      'active_nav' => 'pergantian_varian_retort'
    );
    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_tambah', $data);
    $this->load->view('partials/footer');
  }


  public function detail_retort($tanggal)
  {
    $data = array(
      'data' => $this->pg_varian_model->get_by_tanggal($tanggal),
      'nav' => $this->pg_varian_model->get_nav_by_tanggal($tanggal),
      'active_nav' => 'pergantian_varian_retort',
    );

    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';

    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_detail', $data);
    $this->load->view('partials/footer');
  }


  public function edit_retort($uuid)
  {
    $rules = $this->pg_varian_model->rules_1();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {

      $r = $this->pg_varian_model->get_by_uuid($uuid);
      $update = $this->pg_varian_model->update_retort($uuid);
      if ($update) {
        $this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil di ubah.');
        redirect('pergantian_varian_retort/detail/' . $r->tanggal);
      } else {
        $this->session->set_flashdata('error_msg', 'Data Pergantian Varian gagal di ubah.');
        redirect('pergantian_varian_retort/detail/' . $r->tanggal);
      }
    }

    $data = array(
      'data' => $this->pg_varian_model->get_by_uuid($uuid),
      'active_nav' => 'pergantian_varian_retort'
    );
    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_edit', $data);
    $this->load->view('partials/footer');
  }


  public function approval_retort($tanggal, $role)
  {
    // Validasi input
    if (empty($tanggal) || empty($role)) {
      echo json_encode([
        'status' => false,
        'message' => 'Tanggal atau role tidak valid.',
      ]);
      return;
    }

    $update = $this->pg_varian_model->approval_retort($tanggal, $role);

    if ($update) {
      $current_user = $this->auth_model->current_user();
      echo json_encode([
        'status' => true,
        'fullname' => $current_user->fullname,
        'message' => ucfirst($role) . ' berhasil di-ACC.',
      ]);
    } else {
      echo json_encode([
        'status' => false,
        'message' => 'Gagal menyetujui ' . $role . '. Pastikan data tersedia.',
      ]);
    }
  }



  public function approve_spv($tanggal, $varian_uuid)
  {
    $response = $this->Rr_cooking_model->update_spv($tanggal, $varian_uuid);

    if ($response) {
      $result = [
        'status' => true,
        'fullname' => $this->auth_model->current_user()->fullname // Ganti dengan data fullname pengguna saat ini
      ];
    } else {
      $result = [
        'status' => false,
        'message' => 'Gagal memperbarui data'
      ];
    }

    echo json_encode($result);
  }


  public function form_retort($tanggal)
  {
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    $val = $this->pg_varian_model->get_by_tanggal($tanggal);
    $logo = base_url("assets/img/cpi-logo.jpg");

    $html = '
  <html>
  <head>
  <title>FR-Prod-23 PERGANTIAN VARIAN AREA RETORT</title>
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
  </style>
  <table width="100%">
      <tr>
      <td width="70">
      <table width="100%">
      <tbody>
      <tr>
      <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="90px"></td>
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
              <td style="text-align:center;border:0; text-transform: uppercase;"><h2>PERGANTIAN VARIAN AREA RETORT</h2></td>
          </tr>
      </tbody>
      </table>
      </td>
  <td>
  <table width="101%" style="margin-left:-1px;">
  <tbody>
  <tr>
  <td style="border:0;height:28px;">&nbsp;No. Dokumen</td>
  <td style="border:0;height:28px;">:</td>
  <td style="border:0;height:28px;">&nbsp;FR-Prod-23</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
  <td style="border-left:0;border-right:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
  <td style="border-left:0;border-right:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;28/08/2021</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;1 dari 1</td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  </table>

  <table>
  <table style="padding-top:10px; padding-bottom:10px;">
  <tr>
      <td style="border:none; width:60px; text-align:left;">&nbsp;Tanggal</td>
      <td style="border:none; width:10px; text-align:left;">: </td>
      <td style="border:none; text-align:left;">' . $val[0]->tgl . '</td>
  </tr>
  </tbody>
  </table>
  <table class="data">
  <thead>
  <tr>
  <th rowspan="2">No.</th>
  <th colspan="2">Dari Proses Produksi</th>
  <th colspan="2">Ke Proses Produksi</th>
  <th colspan="2">Kondisi</th>
  <th rowspan="2">Keterangan</th>
  <th colspan="2">TTD</th>
  </tr>
  <tr>
  <th>Varian</th>
  <th>Kode Batch</th>
  <th>Varian</th>
  <th>Kode Batch</th>
  <th style="width:40px;">Bersih dari Kontaminasi</th>
  <th style="width:40px;">Belum Bersih dari Kontaminasi</th>
  <th style="width:70px;">KR/Checker</th>
  <th style="width:70px;">QC</th>
  </thead>
  <tbody>';
    $no = 1;
    foreach ($val as $row) {

      $html .= '<tr style="text-align: center;">
    <td width="1">' . $no . '</td>
    <td>' . $row->varian_name_1 . '</td>
    <td>' . $row->uuid_kode_prod_1 . '</td>
    <td>' . $row->varian_name_2 . '</td>
    <td>' . $row->uuid_kode_prod_2 . '</td>
    <td style="font-size: 12px;">' . $row->kondisi_1 . '</td>
    <td style="font-size: 12px;">' . $row->kondisi_2 . '</td>
    <td>' . $row->keterangan . '</td>
    <td>' . (!empty($row->fullname) ? $row->fullname : '-') . '</td>
    <td>' . $row->acc_qc . '</td>

    </tr>';

      $no++;
    }

    $html .= '</tbody></table>';
    $html .= '<br><table style="padding-top:5px; padding-left:30px; padding-right:30px;" width="100%">
  <tr>

  <td style="width: 100px; text-align: center; font-weight: bold; background-color: #dbe5f1;">Mengetahui</td>
  <td style="border: none; width: 30px;"></td>
  <td style="width: 100px; text-align: center; font-weight: bold; background-color: #dbe5f1;">Disetujui</td>
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
    $dompdf->setPaper('FOLIO', 'potrait');
    $dompdf->render();
    $dompdf->stream("FR-Prod-23_Formulir-Retort_.pdf", array("Attachment" => false));
  }

  public function Packing()
  {
    $data = array(
      'data' => $this->pg_varian_model->get_all_packing(),
      'active_nav' => 'pergantian_varian_packing'
    );
    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_varian_packing', $data);
    $this->load->view('partials/footer');
  }

  public function detail_packing($tanggal, $shift)
  {
    $pg_varian_data = $this->pg_varian_model->get_varian_by_sortasi($tanggal, $shift);
    // $sortasi = $this->pg_varian_model->get_where_sortasi($tanggal, $shift);
    // $update_pg_varian = $this->pg_varian_model->get_pg_varian($tanggal, $shift);
    // $nav_data = $this->pg_varian_model->get_spv_kr($tanggal, $shift);

    $data = [
      'data' => $pg_varian_data,
      // 'sortasi' => $sortasi,
      // 'varian' => $update_pg_varian,
      // 'nav' => $nav_data,
      'active_nav' => 'pergantian_varian_packing'
    ];
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";

    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_detail_packing', $data);
    $this->load->view('partials/footer', $data);
  }

  public function tambah_packing($uuid)
  {
    $rules = $this->pg_varian_model->rules_1();
    $this->form_validation->set_rules($rules);
    $tanggal = $this->input->post('tanggal');
    $shift = $this->input->post('shift');
    $url = $this->pg_varian_model->get_pg_varian($uuid);

    if ($this->form_validation->run() === TRUE) {
      $insert = $this->pg_varian_model->insert_packing();

      if ($insert) {
        $this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil di tambah.');
        redirect('pergantian_varian_packing/detail/' . $url->tanggal . '/' . $url->shift);
      } else {
        $this->session->set_flashdata('error_msg', 'Data Pergantian Varian gagal di tambah.');
        redirect('pergantian_varian_packing/detail/' . $url->tanggal . '/' . $url->shift);
      }
    }

    $data = array(
      'varian' => $this->pg_varian_model->get_pg_varian($uuid),
      'active_nav' => 'pergantian_varian_packing'
    );
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_tambah_packing', $data);
    $this->load->view('partials/footer');
  }

  public function edit_packing($uuid)
  {
    $rules = $this->pg_varian_model->rules_1();
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() === TRUE) {

      $tanggal = $this->input->post('tanggal');
      $shift = $this->input->post('shift');
      $url = $this->pg_varian_model->get_by_uuid_area_packing($uuid);

      $update = $this->pg_varian_model->update_packing($uuid);

      if ($update) {
        $this->session->set_flashdata('success_msg', 'Data Pergantian Varian berhasil di ubah.');
        redirect('pergantian_varian_packing/detail/' . $url->tanggal . '/' . $url->shift);
      } else {
        $this->session->set_flashdata('error_msg', 'Data Pergantian Varian gagal di ubah.');
        redirect('pergantian_varian_packing/detail/' . $url->tanggal . '/' . $url->shift);
      }
    }

    $data = array(
      'data' => $this->pg_varian_model->get_by_uuid_area_packing($uuid),
      'active_nav' => 'pergantian_varian_packing'
    );
    // echo "<pre>";
    // print_r($data);
    // echo '</pre>';
    $this->load->view('partials/head-form', $data);
    $this->load->view('pg_varian/pg_edit_packing', $data);
    $this->load->view('partials/footer');
  }

  public function form_packing($tanggal, $shift)
  {
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);

    $val = $this->pg_varian_model->get_varian_by_sortasi($tanggal, $shift);
    $logo = base_url("assets/img/cpi-logo.jpg");

    $html = '
  <html>
  <head>
  <title>FR-Prod-23 PERGANTIAN VARIAN AREA PACKING</title>
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
  </style>
    <table width="100%">
      <tr>
      <td width="70">
      <table width="100%">
      <tbody>
      <tr>
      <td rowspan="2" align="center" valign="middle" style="border:0;"><img src="' . $logo . '" width="90px"></td>
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
              <td style="text-align:center;border:0; text-transform: uppercase;"><h2>PERGANTIAN VARIAN AREA PACKING</h2></td>
          </tr>
      </tbody>
      </table>
      </td>
  <td>
  <table width="101%" style="margin-left:-1px;">
  <tbody>
  <tr>
  <td style="border:0;height:28px;">&nbsp;No. Dokumen</td>
  <td style="border:0;height:28px;">:</td>
  <td style="border:0;height:28px;">&nbsp;FR-Prod-23</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;Revisi</td>
  <td style="border-left:0;border-right:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;0</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;Tanggal Efektif</td>
  <td style="border-left:0;border-right:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;height:28px;">&nbsp;28/08/2021</td>
  </tr>
  <tr>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;Halaman</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">:</td>
  <td style="border-left:0;border-right:0;border-bottom:0;height:28px;">&nbsp;1 dari 1</td>
  </tr>
  </tbody>
  </table>
  </td>
  </tr>
  </table>

  <table style="padding-top:10px; padding-bottom:10px;">
  <tbody>
  <tr>
      <td style="border:none; width:60px; text-align:left;">&nbsp;Tanggal</td>
      <td style="border:none; width:10px; text-align:left;">: </td>
      <td style="border:none; text-align:left;">' . $val[0]->tgl . '</td>
  </tr>
  <tr>
  <td style="border:none;">&nbsp;Shift</td>
  <td style="border:none;">: </td>
  <td style="border:none;">' . $val[0]->shift_name . '</td>
  </tr>
  </tbody>
  </table>
  <table class="data" width="100%" >
  <thead>
  <tr>
  <th rowspan="2">No.</th>
  <th colspan="2">Dari Proses Sortasi</th>
  <th colspan="2">Ke Proses Sortasi</th>
  <th colspan="2">Kondisi</th>
  <th rowspan="2" style="width:50px;">Waktu</th>
  <th rowspan="2">Keterangan</th>
  <th colspan="2">TTD</th>
  </tr>
  <tr>
  <th>Varian</th>
  <th>Kode Batch</th>
  <th>Varian</th>
  <th>Kode Batch</th>
  <th style="width:40px;">Bersih dari Kontaminasi</th>
  <th style="width:40px;">Belum Bersih dari Kontaminasi</th>
  <th style="width:70px;">KR/Checker</th>
  <th style="width:70px;">QC</th>
  </tr>
  </thead>
  <tbody>';
    $no = 1;
    foreach ($val as $row) {
      $html .= '<tr style="text-align: center;">
        <td width="1">' . $no . '</td>
        <td>' . $row->varian_1 . '</td>
        <td>' . $row->kode_prod . '</td>
        <td>' . (!empty($row->varian_2) ? $row->varian_2 : '-') . '</td>
        <td>' . (!empty($row->kode_prod_2) ? $row->kode_prod_2 : '-') . '</td>
        <td style="font-size: 12px;">' . (!empty($row->kondisi_1) ? $row->kondisi_1 : '-') . '</td>
        <td style="font-size: 12px;">' . (!empty($row->kondisi_2) ? $row->kondisi_2 : '-') . '</td>
        <td>' . $row->jam_mulai . ' - ' . $row->jam_selesai . '</td>
        <td>' . (!empty($row->pg_keterangan) ? $row->pg_keterangan : '-') . '</td>
        <td>' . (!empty($row->fullname) ? $row->fullname : '-') . '</td>
        <td>' . (!empty($row->qc_id) ? $row->qc_id : '-') . '</td>
    </tr>';
      $no++;
    }
    $html .= '</tbody></table>';
    $html .= '<br><table style="padding-top:5px; padding-left:30px; padding-right:30px;" width="100%">
  <tr>

  <td style="width: 100px; text-align: center; font-weight: bold; background-color: #dbe5f1;">Mengetahui</td>
  <td style="border: none; width: 30px;"></td>
  <td style="width: 100px; text-align: center; font-weight: bold; background-color: #dbe5f1;">Disetujui</td>
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
    $dompdf->setPaper('FOLIO', 'potrait');
    $dompdf->render();
    $dompdf->stream("FR-Prod-23_Formulir-Retort_.pdf", array("Attachment" => false));
  }
}
